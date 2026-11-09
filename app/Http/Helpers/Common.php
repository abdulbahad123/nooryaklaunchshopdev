<?php

namespace App\Http\Helpers;

use PDF;
use App\Models\Language;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserOrder;
use App\Models\User\UserShippingCharge;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User\ProductVariantOption;
use App\Models\User\UserItemContent;
use App\Models\User\UserOrderItem;
use Carbon\Carbon;
use App\Models\User\BasicSetting;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserShopSetting;
use DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Common
{
    use AuthorizesRequests, ValidatesRequests;

    public static function sendMailFacadeMail($request, $file_name, $be, $subject, $body, $email, $name)
    {
        /******** Send mail to user ********/
        $data = [];
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_port'] = $be->smtp_port;
        $data['encryption'] = $be->encryption;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;

        //mail info in array
        $data['from_mail'] = $be->from_mail;
        $data['recipient'] = $email;
        $data['subject'] = $subject;
        $data['body'] = $body;
        BasicMailer::sendMail($data);
        if ($file_name) {
            @unlink(public_path('assets/front/invoices/') . $file_name);
        }
    }

    public static function makeInvoice($request, $key, $member, $password, $amount, $payment_method, $phone, $base_currency_text_position, $base_currency_symbol, $base_currency_text, $order_id, $package_title, $status)
    {
        $file_name = uniqid($key) . ".pdf";
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('pdf.membership', compact('request', 'member', 'password', 'amount', 'payment_method', 'phone', 'base_currency_text_position', 'base_currency_symbol', 'base_currency_text', 'order_id', 'package_title', 'status'));
        $output = $pdf->output();
        $dir = public_path('assets/front/invoices/');
        @mkdir($dir, '0775', true);
        @file_put_contents($dir . $file_name, $output);

        // Send parallel WhatsApp invoice notification (strictly deduplicated by cache key to prevent duplicate delivery)
        try {
            $targetPhone = !empty($phone) ? $phone : (
                (is_object($member) && !empty($member->phone)) ? $member->phone : (
                    (is_object($member) && !empty($member->phone_number)) ? $member->phone_number : (
                        (is_array($request) && !empty($request['phone'])) ? $request['phone'] : ''
                    )
                )
            );

            $orderRef = !empty($order_id) ? $order_id : $file_name;
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$targetPhone);
            $dedupCacheKey = 'wa_invoice_sent_' . md5($orderRef . '_' . $cleanPhone);

            if (!empty($targetPhone) && !\Illuminate\Support\Facades\Cache::has($dedupCacheKey) && class_exists('\App\Services\WhatsAppNotificationService')) {
                \Illuminate\Support\Facades\Cache::put($dedupCacheKey, true, 300);

                $invoiceUrl = url('assets/front/invoices/' . $file_name);
                $formattedAmount = ($base_currency_text_position == 'left' ? ($base_currency_symbol ?: $base_currency_text) . ' ' : '')
                    . $amount
                    . ($base_currency_text_position == 'right' ? ' ' . ($base_currency_symbol ?: $base_currency_text) : '');

                $agency = function_exists('getAgencyFromHost') ? getAgencyFromHost() : null;
                $prodName = ($agency && !empty($agency->name)) ? $agency->name : 'Ecombuilder';

                \App\Services\WhatsAppNotificationService::sendInvoice(
                    $targetPhone,
                    $order_id,
                    $formattedAmount,
                    $package_title,
                    $payment_method,
                    $invoiceUrl,
                    $prodName,
                    $agency
                );
            }
        } catch (\Throwable $waInvEx) {
            \Log::warning('LaunchShop Invoice WhatsApp failed: ' . $waInvEx->getMessage());
        }
        return $file_name;
    }

    public static function resetPasswordMail($email, $name, $subject, $body)
    {
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $be = $currentLang->basic_extended;
        /******** Send mail to user ********/
        $data = [];
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_port'] = $be->smtp_port;
        $data['encryption'] = $be->encryption;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;

        //mail info in array
        $data['from_mail'] = $be->from_mail;
        $data['recipient'] = $email;
        $data['subject'] = $subject;
        $data['body'] = $body;
        BasicMailer::sendMail($data);
    }

    // items checkout
    public static function orderTotal($shipping, $user_id)
    {
        if (!empty($shipping) && $shipping != 0) {
            $shippingObj = UserShippingCharge::find($shipping);
            $shippig_charge = $shippingObj ? $shippingObj->charge : 0;
        } else {
            $shippig_charge = 0;
        }

        //cartTotal
        $cartTotal =  Self::cartTotal($user_id);
        //tax
        $tax =  Self::tax($user_id);

        $total = round(($cartTotal - coupon()) + $shippig_charge + $tax, 2);

        return round($total, 2);
    }

    public static function tax($user_id)
    {
        $userObj  = app()->bound('user') ? app('user') : null;
        $username = ($userObj && is_object($userObj) && !empty($userObj->username) && $userObj->username !== 'guest') ? $userObj->username : '';
        if (empty($username)) {
            $u = getUser();
            $username = ($u && is_object($u) && !empty($u->username)) ? $u->username : '';
        }
        $bex = UserShopSetting::where('user_id', $user_id)->first();
        $taxRate = $bex ? (float)$bex->tax : 0;
        $tax = $taxRate;
        if ($username && session()->has('cart_' . $username) && !empty(session()->get('cart_' . $username))) {
            $cartSubTotal =  Self::cartSubTotal($user_id);
            $tax = ($cartSubTotal * $taxRate) / 100;
        }

        return round($tax, 2);
    }

    public static function cartSubTotal($user_id)
    {
        $userObj  = app()->bound('user') ? app('user') : null;
        $username = ($userObj && is_object($userObj) && !empty($userObj->username) && $userObj->username !== 'guest') ? $userObj->username : '';
        if (empty($username)) {
            $u = getUser();
            $username = ($u && is_object($u) && !empty($u->username)) ? $u->username : '';
        }
        $coupon = $username && session()->has('user_coupon_' . $username) && !empty(session()->get('user_coupon_' . $username)) ? session()->get('user_coupon_' . $username) : 0;
        //cartTotal
        $cartTotal =  Self::cartTotal($user_id);
        $subTotal = $cartTotal - $coupon;

        return round($subTotal, 2);
    }
    public static function tax_percentage($user_id)
    {
        $bex = UserShopSetting::where('user_id', $user_id)->first();
        return $bex ? (float)$bex->tax : 0;
    }
    public static function cartTotal($user_id)
    {
        $userObj  = app()->bound('user') ? app('user') : null;
        $username = ($userObj && is_object($userObj) && !empty($userObj->username) && $userObj->username !== 'guest') ? $userObj->username : '';
        if (empty($username)) {
            $u = getUser();
            $username = ($u && is_object($u) && !empty($u->username)) ? $u->username : '';
        }
        $total = 0;
        if ($username && session()->has('cart_' . $username) && !empty(session()->get('cart_' . $username))) {
            $cart = session()->get('cart_' . $username);

            if (!is_null($cart) && is_array($cart)) {
                $cart = array_filter($cart, function ($item) use ($user_id) {
                    return isset($item['user_id']) && $item['user_id'] == $user_id;
                });
                foreach ($cart as $key => $cartItem) {
                    $total += $cartItem['total'] ?? 0;
                }
            }
        }

        return round($total, 2);
    }

    public static function orderValidation($request, $gtype = 'online', $user_id = null)
    {
        $rules = [
            'billing_fname' => 'required',
            'billing_lname' => 'required',
            'billing_number' => 'required',
            'billing_email' => 'required',
            'billing_city' => 'required',
            'billing_country' => 'required',
            'billing_address' => 'required',
            'payment_method' => 'required',

            'shipping_fname' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_lname' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_number' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_email' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_city' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_country' => $request->checkbox == 'on' ? 'required' : '',
            'shipping_address' => $request->checkbox == 'on' ? 'required' : '',
            'identity_number' => $request->payment_method == 'Iyzico' ? 'required' : '',
            'zip_code' => $request->payment_method == 'Iyzico' ? 'required' : '',
        ];

        if ($gtype == 'offline') {
            $gateway = UserOfflineGateway::where([['name', $request->payment_method], ['user_id', $user_id]])->first();

            if ($gateway->is_receipt == 1) {
                $rules['receipt'] = [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $ext = $request->file('receipt')->getClientOriginalExtension();
                        if (!in_array($ext, array('jpg', 'png', 'jpeg'))) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    },
                ];
            }
        }

        $request->validate($rules);
    }

    public static function saveOrder($request, $txnId, $chargeId, $paymentStatus = 'Pending', $gtype = 'online', $user_id)
    {
        $userObj  = app()->bound('user') ? app('user') : null;
        $username = ($userObj && is_object($userObj) && !empty($userObj->username) && $userObj->username !== 'guest') ? $userObj->username : '';
        if (empty($username)) {
            // Fallback: resolve from myfatoorah session or getUser()
            if (Session::has('myfatoorah_user')) {
                $u = Session::get('myfatoorah_user');
            } else {
                $u = getUser();
            }
            $username = ($u && is_object($u) && !empty($u->username)) ? $u->username : '';
        }
        $shpp_chrg = 0;
        if (is_array($request) && !empty($request["shipping_charge"])) {
            $shpp_chrg = $request["shipping_charge"];
        } elseif (is_object($request) && !empty($request->shipping_charge)) {
            $shpp_chrg = $request->shipping_charge;
        } elseif (is_object($request) && !empty($request->shipping_id)) {
            $shpp_chrg = $request->shipping_id;
        }
        $total = Common::orderTotal($shpp_chrg, $user_id);

        $coupon_amount = session()->get('user_coupon_' . $username);
        $total = $total - session()->get('user_coupon_' . $username);
        if (!empty($shpp_chrg) && $shpp_chrg != 0) {
            $shipping = UserShippingCharge::find($shpp_chrg);
            if ($shipping) {
                $shippig_charge = currency_converter_shipping($shipping->charge, $shipping->id);
                $shipping_method = $shipping->title;
            } else {
                $shippig_charge = 0;
                $shipping_method = NULL;
            }
        } else {
            $shippig_charge = 0;
            $shipping_method = NULL;
        }


        if (Session::has('myfatoorah_user')) {
            $user = Session::get('myfatoorah_user');
        } else {
            $user = getUser();
        }

        $timeZone = DB::table('user_basic_settings')->where('user_id', $user->id)->value('timezone');
        $now = Carbon::now($timeZone);

        $order_status = 'pending';
        $cart = session()->get('cart_' . $user->username, []);
        if (count($cart) == 1) {
            foreach ($cart as $itemCart)
                $itemType = UserItem::where([['user_id', $user->id], ['id', $itemCart['id']]])->pluck('type')->first();
            if ($itemType == 'digital') {
                $order_status = 'completed';
            }
        }

        $authCust = Auth::guard('customer')->check() ? Auth::guard('customer')->user() : null;
        $order = new UserOrder();
        $order->customer_id = $authCust ? $authCust->id : 9999999;
        $order->user_id = $user->id;
        $order->billing_fname = !empty($request['billing_fname']) ? $request['billing_fname'] : ($authCust ? ($authCust->billing_fname ?: $authCust->first_name) : '');
        $order->billing_lname = !empty($request['billing_lname']) ? $request['billing_lname'] : ($authCust ? ($authCust->billing_lname ?: $authCust->last_name) : '');
        $order->billing_email = !empty($request['billing_email']) ? $request['billing_email'] : ($authCust ? ($authCust->billing_email ?: $authCust->email) : '');
        $order->billing_address = !empty($request['billing_address']) ? $request['billing_address'] : ($authCust ? ($authCust->billing_address ?: $authCust->address) : '');
        $order->billing_city = !empty($request['billing_city']) ? $request['billing_city'] : ($authCust ? ($authCust->billing_city ?: $authCust->city) : '');
        $order->billing_state = !empty($request['billing_state']) ? $request['billing_state'] : ($authCust ? ($authCust->billing_state ?: $authCust->state) : '');
        $order->billing_country = !empty($request['billing_country']) ? $request['billing_country'] : ($authCust ? ($authCust->billing_country ?: $authCust->country) : '');
        $order->billing_number = !empty($request['billing_number']) ? $request['billing_number'] : ($authCust ? ($authCust->billing_number ?: $authCust->contact_number) : '');
        $order->shipping_fname = !empty($request['shipping_fname']) ? $request['shipping_fname'] : $order->billing_fname;
        $order->shipping_lname = !empty($request['shipping_lname']) ? $request['shipping_lname'] : $order->billing_lname;
        $order->shipping_email = !empty($request['shipping_email']) ? $request['shipping_email'] : $order->billing_email;
        $order->shipping_address = !empty($request['shipping_address']) ? $request['shipping_address'] : $order->billing_address;
        $order->shipping_city = !empty($request['shipping_city']) ? $request['shipping_city'] : $order->billing_city;
        $order->shipping_state = !empty($request['shipping_state']) ? $request['shipping_state'] : $order->billing_state;
        $order->shipping_country = !empty($request['shipping_country']) ? $request['shipping_country'] : $order->billing_country;
        $order->shipping_number = !empty($request['shipping_number']) ? $request['shipping_number'] : $order->billing_number;
        $order->order_status = $order_status;
        $order->gateway_type = $gtype;
        if (is_array($request)) {
            if (array_key_exists('conversation_id', $request)) {
                $conversation_id = $request['conversation_id'];
            } else {
                $conversation_id = null;
            }
        } else {
            $conversation_id = null;
        }
        $order->conversation_id = $conversation_id;
        $order->cart_total = Self::cartTotal($user->id);
        $order->tax = Self::tax($user->id);
        $order->tax_percentage = Self::tax_percentage($user->id);
        $order->discount = $coupon_amount;
        $order->total = $total;
        $order->shipping_method = $shipping_method;
        $order->shipping_charge = round($shippig_charge, 2);
        if ($gtype == 'online') {
            $order->method = $request['payment_method'];
        } elseif ($gtype == 'offline') {
            $gateway =  UserOfflineGateway::where([['user_id', $user->id], ['name', $request['payment_method']]])
                ->first();
            $order->method = $gateway->name;
            if ($request->hasFile('receipt')) {
                $dir = public_path('assets/front/receipt/');
                $receipt = Uploader::upload_picture($dir, $request->file('receipt'));
                $order->receipt = $receipt;
            } else {
                $order->receipt = NULL;
            }
        }
        $userCurrentCurr = app('userCurrentCurr');
        $CurrentCurr = UserCurrency::where('id', $userCurrentCurr->id)->first();
        if (empty($CurrentCurr)) {
            $CurrentCurr = UserCurrency::where('user_id', $user->id)->where('is_default', 1)->first()
                ?? UserCurrency::where('user_id', $user->id)->first();
        }
        $order->currency_code = $CurrentCurr->text ?? ($userCurrentCurr->text ?? 'INR');
        $order->currency_text_position = $CurrentCurr->text_position ?? ($userCurrentCurr->text_position ?? 'left');
        $order->currency_sign = $CurrentCurr->symbol ?? ($userCurrentCurr->symbol ?? '₹');
        $order->currency_position = $CurrentCurr->symbol_position ?? ($userCurrentCurr->symbol_position ?? 'left');
        $order['currency_id'] = $CurrentCurr->id ?? ($userCurrentCurr->id ?? 1);
        $order['order_number'] = \Str::random(4) . time();
        $order['payment_status'] = $paymentStatus;
        $order['txnid'] = $txnId;
        $order['charge_id'] = $chargeId;
        $order->created_at = $now;
        $order->updated_at = $now;
        $order->save();

        return $order;
    }

    public static function saveOrderedItems($orderId)
    {
        if (Session::has('myfatoorah_user')) {
            $user = Session::get('myfatoorah_user');
        } else {
            $user = getUser();
        }

        $cart = Session::get('cart_' . $user->username);
        if (empty($cart) || !is_array($cart)) {
            return;
        }
        $items = [];
        $qty = [];
        $variations = [];
        foreach ($cart as $id => $item) {
            $qty[] = $item['qty'];
            $variations[] = json_encode($item['variations']);
            $foundItem = UserItem::find($item['id']);
            if ($foundItem) {
                $items[] = $foundItem;
            }
        }

        $userCurrentLang = app('userCurrentLang');

        foreach ($items as $key => $item) {
            if (!empty($item->category)) {
                $category = $item->category->name;
            } else {
                $category = '';
            }
            // Safely fetch item content; fall back to any language content if current lang content is missing
            $itemcontent = null;
            if ($userCurrentLang && isset($userCurrentLang->id)) {
                $itemcontent = UserItemContent::where('item_id', $item->id)->where('language_id', $userCurrentLang->id)->first();
            }
            if (!$itemcontent) {
                $itemcontent = UserItemContent::where('item_id', $item->id)->first();
            }
            $item_price = currency_converter(($item->flash == 1 ?  ($item->current_price - ($item->current_price * ($item->flash_amount / 100))) : $item->current_price), $item->id);

            $orderderd_variations = json_decode($variations[$key]);
            if ($orderderd_variations) {
                foreach ($orderderd_variations as $vkey => $value) {
                    $option = ProductVariantOption::where('id', intval($value->option_id))->first();
                    if ($option) {
                        $option->stock = $option->stock - $qty[$key];
                        $option->save();
                    }
                }
            } else {
                foreach ($cart as $id => $proId) {
                    $product = UserItem::findOrFail($proId['id']);
                    $stock = $product->stock - $proId['qty'];
                    UserItem::where('id', $proId['id'])->update([
                        'stock' => $stock
                    ]);
                }
            }

            $timeZone = DB::table('user_basic_settings')->where('user_id', $user->id)->value('timezone');

            UserOrderItem::insert([
                'user_order_id' => $orderId,
                'customer_id' => Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : 9999999,
                'user_id' => $user->id,
                'item_id' => $item->id,
                'title' => $itemcontent ? $itemcontent->title : ($item->sku ?? ''),
                'sku' => $item->sku,
                'qty' => $qty[$key],
                'variations' => $variations[$key] != 'null' ? $variations[$key] : null,
                'category' => $itemcontent ? $itemcontent->category_id : null,
                'price' => $item_price,
                'previous_price' => $item->previous_price,
                'image' => $item->thumbnail,
                'summary' => $itemcontent ? ($itemcontent->summary ?? '') : '',
                'description' => $itemcontent ? ($itemcontent->description ?? '') : '',
                'created_at' => Carbon::now($timeZone),
            ]);
        }
    }

    public static function sendOrderConfirmationAndInvoice($order, $user = null)
    {
        if (!$user) {
            $user = getUser() ?? User::find($order->user_id);
        }
        if (!$user) {
            return;
        }

        // Ensure order items relation is loaded
        if (!$order->relationLoaded('orderitems')) {
            $order->load('orderitems');
        }

        // Ensure invoice PDF exists
        if (empty($order->invoice_number) || !file_exists(public_path('assets/front/invoices/' . $order->invoice_number))) {
            try {
                $order->invoice_number = static::generateInvoice($order, $user);
                $order->save();
            } catch (\Throwable $e) {
                \Log::warning('Invoice PDF generation failed: ' . $e->getMessage());
            }
        }

        // Resolve buyer email (supports Guest via instant order and registered customer)
        $buyerEmail = !empty($order->billing_email) ? trim($order->billing_email) : '';
        if (empty($buyerEmail) && !empty($order->customer_id) && $order->customer_id != 9999999) {
            $cust = \App\Models\Customer::find($order->customer_id);
            if ($cust) {
                $buyerEmail = trim($cust->billing_email ?: $cust->email);
            }
        }
        if (empty($buyerEmail) && !empty($order->shipping_email)) {
            $buyerEmail = trim($order->shipping_email);
        }

        if (empty($buyerEmail)) {
            \Log::warning("sendOrderConfirmationAndInvoice: Recipient email is empty for order #{$order->order_number}");
            return;
        }

        // Resolve customer display name
        $customerName = trim(($order->billing_fname ?? '') . ' ' . ($order->billing_lname ?? ''));
        if (empty($customerName) && !empty($order->customer_id) && $order->customer_id != 9999999) {
            $cust = \App\Models\Customer::find($order->customer_id);
            if ($cust) {
                $customerName = trim(($cust->first_name ?? '') . ' ' . ($cust->last_name ?? ''));
            }
        }
        if (empty($customerName)) {
            $customerName = trim(($order->shipping_fname ?? '') . ' ' . ($order->shipping_lname ?? ''));
        }
        if (empty($customerName)) {
            $customerName = 'Valued Customer';
        }

        $userBs = BasicSetting::where('user_id', $user->id)->first();
        $websiteTitle = $userBs->website_title ?? $user->shop_name ?? $user->username ?? 'Store';

        // Check if custom template exists in db
        $mailTemplate = UserEmailTemplate::where([['email_type', 'product_order'], ['user_id', $user->id]])->first();
        $subject = ($mailTemplate && !empty($mailTemplate->email_subject))
            ? str_replace(['{order_number}', '{customer_name}', '{website_title}'], [$order->order_number, $customerName, $websiteTitle], $mailTemplate->email_subject)
            : "Order Confirmation - #{$order->order_number} | {$websiteTitle}";

        // Render dedicated order confirmation & invoice email view
        $htmlBody = view('mail.order_confirmation', [
            'order'         => $order,
            'user'          => $user,
            'userBs'        => $userBs,
            'customerName'  => $customerName,
            'website_title' => $websiteTitle,
            'subject'       => $subject,
        ])->render();

        $info = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $data = [
            'smtp_status'   => $info->is_smtp ?? 1,
            'smtp_host'     => $info->smtp_host ?? env('MAIL_HOST'),
            'smtp_port'     => $info->smtp_port ?? env('MAIL_PORT', 465),
            'encryption'    => $info->encryption ?? env('MAIL_ENCRYPTION', 'ssl'),
            'smtp_username' => $info->smtp_username ?? env('MAIL_USERNAME'),
            'smtp_password' => $info->smtp_password ?? env('MAIL_PASSWORD'),
            'from_mail'     => $info->from_mail ?? env('MAIL_FROM_ADDRESS'),
            'from_name'     => $info->from_name ?? $websiteTitle,
            'recipient'     => $buyerEmail,
            'subject'       => $subject,
            'body'          => $htmlBody,
        ];

        if (!empty($order->invoice_number) && file_exists(public_path('assets/front/invoices/' . $order->invoice_number))) {
            $data['invoice'] = public_path('assets/front/invoices/' . $order->invoice_number);
        }

        try {
            BasicMailer::sendMail($data);
        } catch (\Throwable $e) {
            \Log::warning("Order confirmation email failed for #{$order->order_number}: " . $e->getMessage());
        }

        Session::forget('cart_' . $user->username);
        Session::forget('coupon');
        Session::forget('user_coupon_' . $user->username);
    }

    public static function sendMails($order)
    {
        return static::sendOrderConfirmationAndInvoice($order);
    }

    public static function generateInvoice($order, $user)
    {
        $data['userBs'] = BasicSetting::where('user_id', $user->id)->first();
        $data['user'] = $user;
        $fileName = \Str::random(4) . time() . '.pdf';
        $dir = public_path('assets/front/invoices/');
        $path = $dir . $fileName;
        @mkdir($dir, 0777, true);
        $data['order']  = $order;
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('pdf.item', $data)->save($path);
        UserOrder::where('id', $order->id)->update([
            'invoice_number' => $fileName
        ]);
        return $fileName;
    }

    public static function OrderCompletedMail($order, $user = null)
    {
        return static::sendOrderConfirmationAndInvoice($order, $user);
    }

    public static function getUserCurrentCurrency($userId)
    {
        $curr = null;
        if (app()->bound('userCurrentCurr')) {
            $curr = app('userCurrentCurr');
        }
        if (!empty($curr) && is_object($curr) && isset($curr->user_id) && $curr->user_id == $userId) {
            return $curr;
        }
        if (!empty($userId)) {
            $userCurr = UserCurrency::where('user_id', $userId)->where('is_default', 1)->first();
            if (empty($userCurr)) {
                $userCurr = UserCurrency::where('user_id', $userId)->first();
            }
            if ($userCurr) {
                return $userCurr;
            }
        }
        return $curr ?? (app()->bound('userCurrentCurr') ? app('userCurrentCurr') : null);
    }

    public static function get_keywords()
    {
        $userCurrentLang = app('userCurrentLang');
        return json_decode($userCurrentLang->keywords, true);
    }

    public static function wrapEmailBody($body, $subject, $user = null, $customLogoUrl = null, $customTitle = null)
    {
        if (strpos($body, 'email-container') !== false || strpos($body, '<html') !== false) {
            return $body;
        }

        $rawLogoUrl = !empty($customLogoUrl) ? $customLogoUrl : asset('assets/landing_page/ecombuilderemail.png');
        $logo_url = str_starts_with($rawLogoUrl, 'http://') ? str_replace('http://', 'https://', $rawLogoUrl) : $rawLogoUrl;
        if (str_contains($logo_url, 'localhost') || str_contains($logo_url, '127.0.0.1')) {
            $logo_url = !empty($customLogoUrl) ? $customLogoUrl : 'https://ecombuilder.saasreselling.com/assets/landing_page/ecombuilderemail.png';
        }
        $website_title = !empty($customTitle) ? $customTitle : 'Ecom Builder';
        $base_color = 'ff6f61'; // fallback brand color

        if (!empty($user) && is_object($user) && isset($user->id) && empty($user->preview_template)) {
            // Merchant context passed explicitly (only for real merchants, not template demo users)
            $userBs = BasicSetting::where('user_id', $user->id)->first();
            if ($userBs) {
                if (!empty($userBs->logo)) {
                    $uLogo = asset('assets/front/img/user/' . $userBs->logo);
                    $logo_url = str_starts_with($uLogo, 'http://') ? str_replace('http://', 'https://', $uLogo) : $uLogo;
                }
                if (!empty($userBs->base_color)) {
                    $base_color = $userBs->base_color;
                }
            }
            $website_title = $user->shop_name ?? $user->username ?? 'Ecom Builder';
        }

        try {
            return view('mail.styled_layout', compact('body', 'logo_url', 'website_title', 'base_color', 'subject'))->render();
        } catch (\Exception $e) {
            return $body;
        }
    }

    /**
     * Send instant order status notification email (Pending, Completed, Processing, Rejected) to customer's billing email.
     */
    public static function sendOrderStatusEmail($po, $root_user, $newStatus)
    {
        try {
            $user_id = $root_user->id ?? ($po->user_id ?? null);
            $billingEmail = !empty($po->billing_email) ? trim($po->billing_email) : null;
            if (empty($billingEmail) && !empty($po->customer_id)) {
                $cust = \App\Models\Customer::where('id', $po->customer_id)->first();
                if ($cust) {
                    $billingEmail = trim($cust->email);
                }
            }
            if (empty($billingEmail)) {
                return false;
            }

            $customerName = trim(($po->billing_fname ?? '') . ' ' . ($po->billing_lname ?? ''));
            if (empty($customerName) && !empty($po->customer_id)) {
                $cust = $cust ?? \App\Models\Customer::where('id', $po->customer_id)->first();
                if ($cust) {
                    $customerName = trim(($cust->first_name ?? '') . ' ' . ($cust->last_name ?? ''));
                }
            }
            if (empty($customerName)) {
                $customerName = 'Valued Customer';
            }

            $storeTitle = $root_user->shop_name ?? $root_user->username ?? 'Our Store';
            $statusFormatted = ucfirst(strtolower($newStatus));

            // Load user template if configured
            $mail_template = \App\Models\UserEmailTemplate::where([['user_id', $user_id], ['email_type', 'product_order_status']])->first();
            $subject = $mail_template && !empty($mail_template->email_subject)
                ? str_replace(['{order_number}', '{order_status}', '{website_title}'], [$po->order_number, $statusFormatted, $storeTitle], $mail_template->email_subject)
                : ("Update on your Order #{$po->order_number}: {$statusFormatted}");

            $userBs = \App\Models\User\BasicSetting::where('user_id', $user_id)->first();
            $brandColor = !empty($userBs->base_color) ? '#' . str_replace('#', '', $userBs->base_color) : '#059669';

            $statusColor = '#059669';
            if (in_array(strtolower($newStatus), ['rejected', 'cancelled'])) {
                $statusColor = '#dc2626';
            } elseif (in_array(strtolower($newStatus), ['pending'])) {
                $statusColor = '#d97706';
            } elseif (in_array(strtolower($newStatus), ['processing'])) {
                $statusColor = '#2563eb';
            }

            $invoiceUrl = !empty($po->invoice_number) ? asset('assets/front/invoices/' . $po->invoice_number) : '';

            $bodyHtml = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background-color: #f1f5f9; margin: 0; padding: 15px; color: #1e293b; }
                    .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
                    .header-bar { height: 6px; background-color: ' . $brandColor . '; }
                    .header { padding: 24px 20px; text-align: center; border-bottom: 1px solid #f1f5f9; }
                    .status-banner { background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 24px 20px; text-align: center; }
                    .status-badge { display: inline-block; background-color: ' . $statusColor . '; color: #ffffff; font-size: 13px; font-weight: 700; text-transform: uppercase; padding: 5px 14px; border-radius: 9999px; letter-spacing: 0.5px; margin-bottom: 10px; }
                    .content { padding: 24px 20px; font-size: 14px; line-height: 1.6; color: #334155; }
                    .info-box { background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; padding: 16px; margin: 18px 0; }
                    .btn-invoice { display: inline-block; background-color: ' . $brandColor . '; color: #ffffff !important; padding: 12px 24px; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 13px; margin-top: 10px; }
                    .footer { background: #f8fafc; padding: 18px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
                    @media only screen and (max-width: 600px) {
                        body { padding: 0 !important; background: #fff !important; }
                        .card { width: 100% !important; border: none !important; border-radius: 0 !important; }
                        .content { padding: 18px 14px !important; }
                        .btn-invoice { display: block !important; width: 100% !important; box-sizing: border-box !important; text-align: center !important; }
                    }
                </style>
            </head>
            <body>
                <div class="card">
                    <div class="header-bar"></div>
                    <div class="header">
                        <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: #0f172a;">' . htmlspecialchars($storeTitle) . '</h2>
                    </div>
                    <div class="status-banner">
                        <span class="status-badge">Order ' . $statusFormatted . '</span>
                        <h3 style="margin: 6px 0 0; font-size: 18px; font-weight: 700; color: #0f172a;">Your Order is ' . $statusFormatted . '</h3>
                    </div>
                    <div class="content">
                        <p style="margin-top: 0;">Hi <strong>' . htmlspecialchars($customerName) . '</strong>,</p>
                        <p>We are writing to inform you that your order <strong>#' . htmlspecialchars($po->order_number) . '</strong> status has been updated to: <strong style="color: ' . $statusColor . ';">' . $statusFormatted . '</strong>.</p>
                        
                        <div class="info-box">
                            <table width="100%" cellpadding="4" cellspacing="0" style="font-size: 13px; color: #475569;">
                                <tr>
                                    <td><strong>Order Number:</strong></td>
                                    <td align="right">#' . htmlspecialchars($po->order_number) . '</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Status:</strong></td>
                                    <td align="right" style="font-weight: 700; color: ' . $statusColor . ';">' . $statusFormatted . '</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td align="right">' . htmlspecialchars(ucfirst($po->method)) . '</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td align="right" style="font-weight: 700; color: #0f172a;">' . ($po->currency_sign ?? '₹') . number_format((float)$po->total, 2) . '</td>
                                </tr>
                                <tr>
                                    <td><strong>Billing Email:</strong></td>
                                    <td align="right">' . htmlspecialchars($billingEmail) . '</td>
                                </tr>
                            </table>
                        </div>

                        ' . (!empty($invoiceUrl) ? '
                        <div style="text-align: center; margin: 20px 0;">
                            <a href="' . $invoiceUrl . '" target="_blank" class="btn-invoice">📥 Download Updated Invoice (PDF)</a>
                            <div style="font-size: 12px; color: #64748b; margin-top: 8px;">Your official invoice is also attached to this email.</div>
                        </div>' : '') . '

                        <p style="margin-bottom: 0;">If you have any questions or require support, simply reply to this email.</p>
                    </div>
                    <div class="footer">
                        &copy; ' . date('Y') . ' ' . htmlspecialchars($storeTitle) . '. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ';

            $info = \Illuminate\Support\Facades\DB::table('basic_extendeds')
                ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
                ->first();

            $data = [
                'smtp_status'   => $info ? $info->is_smtp : 1,
                'smtp_host'     => $info->smtp_host ?? env('MAIL_HOST'),
                'smtp_port'     => $info->smtp_port ?? env('MAIL_PORT'),
                'encryption'    => $info->encryption ?? env('MAIL_ENCRYPTION'),
                'smtp_username' => $info->smtp_username ?? env('MAIL_USERNAME'),
                'smtp_password' => $info->smtp_password ?? env('MAIL_PASSWORD'),
                'from_mail'     => $info->from_mail ?? env('MAIL_FROM_ADDRESS', 'admin@saasreselling.com'),
                'from_name'     => $storeTitle,
                'recipient'     => $billingEmail,
                'subject'       => $subject,
                'body'          => $bodyHtml,
            ];

            if (!empty($po->invoice_number)) {
                $invFile = public_path('assets/front/invoices/' . $po->invoice_number);
                if (file_exists($invFile)) {
                    $data['invoice'] = $invFile;
                }
            }

            BasicMailer::sendMail($data);
            return true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('sendOrderStatusEmail Exception: ' . $e->getMessage());
            return false;
        }
    }
}
