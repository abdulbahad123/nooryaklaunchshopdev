<?php

namespace App\Http\Controllers\User;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\UserShippingGateway;
use App\Models\User\UserOrder;
use App\Models\Customer;
use App\Http\Helpers\BasicMailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ShippingGatewayController extends Controller
{
    public function index()
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['aftership'] = UserShippingGateway::where([['user_id', $user_id], ['keyword', 'aftership']])->first();
        $data['shiprocket'] = UserShippingGateway::where([['user_id', $user_id], ['keyword', 'shiprocket']])->first();
        $data['shippo'] = UserShippingGateway::where([['user_id', $user_id], ['keyword', 'shippo']])->first();
        return view('user.settings.shipping_gateway.index', $data);
    }

    public function aftershipUpdate(Request $request)
    {
        $gateway = UserShippingGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'aftership']])->first();
        if (empty($gateway)) {
            $gateway = new UserShippingGateway();
            $gateway->name = 'AfterShip';
            $gateway->keyword = 'aftership';
            $gateway->user_id = Auth::guard('web')->user()->id;
        }
        $gateway->status = $request->status;
        $information = [
            'api_key' => $request->api_key,
        ];
        $gateway->information = json_encode($information);
        $gateway->save();
        
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function shiprocketUpdate(Request $request)
    {
        $gateway = UserShippingGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'shiprocket']])->first();
        if (empty($gateway)) {
            $gateway = new UserShippingGateway();
            $gateway->name = 'Shiprocket';
            $gateway->keyword = 'shiprocket';
            $gateway->user_id = Auth::guard('web')->user()->id;
        }
        $gateway->status = $request->status;
        $information = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $gateway->information = json_encode($information);
        $gateway->save();
        
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function shippoUpdate(Request $request)
    {
        $gateway = UserShippingGateway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'shippo']])->first();
        if (empty($gateway)) {
            $gateway = new UserShippingGateway();
            $gateway->name = 'Shippo';
            $gateway->keyword = 'shippo';
            $gateway->user_id = Auth::guard('web')->user()->id;
        }
        $gateway->status = $request->status;
        $information = [
            'api_key' => $request->api_key,
        ];
        $gateway->information = json_encode($information);
        $gateway->save();
        
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function trackingUpdate(Request $request)
    {
        $rules = [
            'order_id' => 'required',
            'courier_name' => 'required',
            'tracking_number' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order = UserOrder::findOrFail($request->order_id);
        $order->courier_name = $request->courier_name;
        $order->tracking_number = $request->tracking_number;
        $order->shipping_gateway_keyword = $request->shipping_gateway_keyword;

        $tracking_url = null;

        if ($request->shipping_gateway_keyword == 'aftership') {
            $gateway = UserShippingGateway::where([['user_id', $order->user_id], ['keyword', 'aftership'], ['status', 1]])->first();
            if ($gateway) {
                $info = json_decode($gateway->information, true);
                $apiKey = $info['api_key'] ?? '';
                if ($apiKey) {
                    try {
                        $client = new \GuzzleHttp\Client();
                        $response = $client->post('https://api.aftership.com/v4/trackings', [
                            'headers' => [
                                'aftership-api-key' => $apiKey,
                                'Content-Type' => 'application/json',
                            ],
                            'json' => [
                                'tracking' => [
                                    'tracking_number' => $request->tracking_number,
                                    'title' => 'Order #' . $order->order_number,
                                ]
                            ]
                        ]);
                        $resData = json_decode($response->getBody()->getContents(), true);
                        if (isset($resData['data']['tracking']['tracking_url'])) {
                            $tracking_url = $resData['data']['tracking']['tracking_url'];
                        }
                    } catch (\Exception $e) {
                        \Log::error('AfterShip Tracking Error: ' . $e->getMessage());
                    }
                }
            }
        } elseif ($request->shipping_gateway_keyword == 'shippo') {
            $gateway = UserShippingGateway::where([['user_id', $order->user_id], ['keyword', 'shippo'], ['status', 1]])->first();
            if ($gateway) {
                $info = json_decode($gateway->information, true);
                $apiKey = $info['api_key'] ?? '';
                if ($apiKey) {
                    try {
                        $client = new \GuzzleHttp\Client();
                        $carrierSlug = strtolower(str_replace(' ', '-', $request->courier_name));
                        $response = $client->post('https://api.goshippo.com/tracks/', [
                            'headers' => [
                                'Authorization' => 'ShippoToken ' . $apiKey,
                                'Content-Type' => 'application/json',
                            ],
                            'json' => [
                                'carrier' => $carrierSlug,
                                'tracking_number' => $request->tracking_number,
                            ]
                        ]);
                        $resData = json_decode($response->getBody()->getContents(), true);
                        if (isset($resData['tracking_status']['tracking_url'])) {
                            $tracking_url = $resData['tracking_status']['tracking_url'];
                        }
                    } catch (\Exception $e) {
                        \Log::error('Shippo Tracking Error: ' . $e->getMessage());
                    }
                }
            }
        } elseif ($request->shipping_gateway_keyword == 'shiprocket') {
            $tracking_url = 'https://shiprocket.co/tracking/' . $request->tracking_number;
        }

        if (empty($tracking_url)) {
            $tracking_url = 'https://www.google.com/search?q=' . urlencode($request->courier_name . ' tracking ' . $request->tracking_number);
        }

        $order->tracking_url = $tracking_url;
        $order->save();

        $customer = Customer::where('id', $order->customer_id)->first();
        $email = $customer ? $customer->email : $order->billing_email;
        $name = $customer ? ($customer->first_name . ' ' . $customer->last_name) : ($order->billing_fname . ' ' . $order->billing_lname);
        
        $root_user = \App\Models\User::find($order->user_id);
        
        if ($email) {
            try {
                $mail_subject = __('Shipment Shipped - Tracking Details');
                $mail_body = '<p>' . __('Hi') . ' ' . $name . ',</p>' .
                            '<p>' . __('Your order') . ' <strong>#' . $order->order_number . '</strong> ' . __('has been shipped via') . ' <strong>' . $request->courier_name . '</strong>.</p>' .
                            '<p>' . __('Tracking Number') . ': <strong>' . $request->tracking_number . '</strong></p>' .
                            '<p>' . __('You can track your shipment using the link below:') . '</p>' .
                            '<p><a href="' . $tracking_url . '" target="_blank" style="padding: 8px 16px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">' . __('Track Order') . '</a></p>';

                $info = DB::table('basic_extendeds')
                    ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
                    ->first();

                $data = [];
                $data['smtp_status'] = $info->is_smtp;
                $data['smtp_host'] = $info->smtp_host;
                $data['smtp_port'] = $info->smtp_port;
                $data['encryption'] = $info->encryption;
                $data['smtp_username'] = $info->smtp_username;
                $data['smtp_password'] = $info->smtp_password;
                $data['from_mail'] = $info->from_mail;
                $data['recipient'] = $email;
                $data['subject'] = $mail_subject;
                $data['body'] = $mail_body;
                BasicMailer::sendMail($data);
            } catch (\Exception $e) {
                \Log::error('Tracking Email Error: ' . $e->getMessage());
            }
        }

        Session::flash('success', __('Tracking Details Saved & Customer Notified!'));
        return back();
    }

    public static function createShiprocketOrder($order, $user_id)
    {
        $gateway = UserShippingGateway::where([['user_id', $user_id], ['keyword', 'shiprocket'], ['status', 1]])->first();
        if (!$gateway) {
            return;
        }

        $info = json_decode($gateway->information, true);
        $email = $info['email'] ?? '';
        $password = $info['password'] ?? '';

        if (empty($email) || empty($password)) {
            return;
        }

        try {
            $client = new \GuzzleHttp\Client();
            
            // Step 1: Login to Shiprocket
            $response = $client->post('https://apiv2.shiprocket.in/v1/external/auth/login', [
                'json' => [
                    'email' => $email,
                    'password' => $password
                ]
            ]);
            
            $loginData = json_decode($response->getBody()->getContents(), true);
            $token = $loginData['token'] ?? '';
            if (empty($token)) {
                Session::flash('warning', 'Shiprocket Error: Failed to retrieve authentication token from response.');
                return;
            }

            // Step 2: Clean up address fields and phone numbers to prevent empty values or formatting errors
            $billing_fname = !empty($order->billing_fname) ? trim($order->billing_fname) : 'Customer';
            $billing_lname = !empty($order->billing_lname) ? trim($order->billing_lname) : '';
            $billing_address = !empty($order->billing_address) ? trim($order->billing_address) : 'Address not provided';
            $billing_city = !empty($order->billing_city) ? trim($order->billing_city) : 'City';
            $billing_state = !empty($order->billing_state) ? trim($order->billing_state) : $billing_city;
            $billing_country = !empty($order->billing_country) ? trim($order->billing_country) : 'India';
            $billing_email = !empty($order->billing_email) ? trim($order->billing_email) : 'email@example.com';
            $billing_phone = !empty($order->billing_number) ? preg_replace('/[^0-9]/', '', $order->billing_number) : '9999999999';
            if (empty($billing_phone)) {
                $billing_phone = '9999999999';
            }

            $shipping_fname = !empty($order->shipping_fname) ? trim($order->shipping_fname) : $billing_fname;
            $shipping_lname = !empty($order->shipping_lname) ? trim($order->shipping_lname) : $billing_lname;
            $shipping_address = !empty($order->shipping_address) ? trim($order->shipping_address) : $billing_address;
            $shipping_city = !empty($order->shipping_city) ? trim($order->shipping_city) : $billing_city;
            $shipping_state = !empty($order->shipping_state) ? trim($order->shipping_state) : $shipping_city;
            $shipping_country = !empty($order->shipping_country) ? trim($order->shipping_country) : $billing_country;
            $shipping_email = !empty($order->shipping_email) ? trim($order->shipping_email) : $billing_email;
            $shipping_phone = !empty($order->shipping_number) ? preg_replace('/[^0-9]/', '', $order->shipping_number) : $billing_phone;
            if (empty($shipping_phone)) {
                $shipping_phone = $billing_phone;
            }

            // Extract shipping pincode (handles 4 to 8 digits)
            preg_match('/\b\d{4,8}\b/', $shipping_address, $matches);
            $shipping_pincode = $matches[0] ?? (preg_match('/\b\d{4,8}\b/', $billing_address, $bMatches) ? $bMatches[0] : '');

            if (empty($shipping_pincode)) {
                Session::flash('warning', 'Shiprocket Error: No valid pincode (4-8 digits) found in the address. Please edit the order address to include a pincode (e.g., 622001).');
                return;
            }

            // Step 3: Fetch order items
            $order_items = \App\Models\User\UserOrderItem::where('user_order_id', $order->id)->get();
            $itemsData = [];
            foreach ($order_items as $item) {
                $itemsData[] = [
                    'name' => $item->title,
                    'sku' => $item->sku ?? ('SKU-' . $item->item_id),
                    'units' => $item->qty,
                    'selling_price' => $item->price,
                    'discount' => 0,
                    'tax' => 0,
                    'hsn' => ''
                ];
            }

            // Step 4: Map payment method
            $paymentMethod = (strtolower($order->method) == 'cod' || strtolower($order->gateway_type) == 'offline') ? 'COD' : 'Prepaid';

            // Step 4.5: Fetch active pickup locations dynamically from Shiprocket
            $pickupLocation = 'Primary';
            try {
                $pickupResponse = $client->get('https://apiv2.shiprocket.in/v1/external/settings/company/pickup', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    ]
                ]);
                $pickupData = json_decode($pickupResponse->getBody()->getContents(), true);
                $hasPickupAddress = false;
                
                $addresses = [];
                if (isset($pickupData['data']['data']) && is_array($pickupData['data']['data'])) {
                    $addresses = $pickupData['data']['data'];
                } elseif (isset($pickupData['data']['shipping_address']) && is_array($pickupData['data']['shipping_address'])) {
                    $addresses = $pickupData['data']['shipping_address'];
                } elseif (isset($pickupData['data']) && is_array($pickupData['data'])) {
                    $addresses = $pickupData['data'];
                }

                if (count($addresses) > 0) {
                    $hasPickupAddress = true;
                    // Find a pickup address
                    foreach ($addresses as $addr) {
                        if (!empty($addr['pickup_location'])) {
                            $pickupLocation = $addr['pickup_location'];
                            break;
                        }
                    }
                }
                
                if (!$hasPickupAddress) {
                    Session::flash('warning', 'Shiprocket Error: No pickup location found in your Shiprocket account. Please log in to Shiprocket, go to Settings -> Pickup Addresses, and add at least one pickup address first.');
                    return;
                }
            } catch (\Exception $e) {
                \Log::warning('Shiprocket Failed to fetch pickup location dynamically: ' . $e->getMessage());
            }

            // Step 5: Send order creation request to Shiprocket
            $orderPayload = [
                'order_id' => $order->order_number,
                'order_date' => $order->created_at->format('Y-m-d H:i'),
                'pickup_location' => $pickupLocation,
                
                'billing_customer_name' => $billing_fname,
                'billing_last_name' => $billing_lname,
                'billing_address' => $billing_address,
                'billing_city' => $billing_city,
                'billing_pincode' => $shipping_pincode,
                'billing_state' => $billing_state,
                'billing_country' => $billing_country,
                'billing_email' => $billing_email,
                'billing_phone' => $billing_phone,
                
                'shipping_is_billing' => false,
                'shipping_customer_name' => $shipping_fname,
                'shipping_last_name' => $shipping_lname,
                'shipping_address' => $shipping_address,
                'shipping_city' => $shipping_city,
                'shipping_pincode' => $shipping_pincode,
                'shipping_state' => $shipping_state,
                'shipping_country' => $shipping_country,
                'shipping_email' => $shipping_email,
                'shipping_phone' => $shipping_phone,

                'order_items' => $itemsData,
                'payment_method' => $paymentMethod,
                'sub_total' => $order->cart_total,
                'length' => 10,
                'breadth' => 10,
                'height' => 10,
                'weight' => 0.5
            ];

            \Log::info('Shiprocket Payload: ' . json_encode($orderPayload));

            $orderResponse = $client->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json'
                ],
                'json' => $orderPayload
            ]);

            $responseBody = $orderResponse->getBody()->getContents();
            \Log::info('Shiprocket API Success Response: ' . $responseBody);
            $resData = json_decode($responseBody, true);
            
            // Save shiprocket order details to user_orders
            if (isset($resData['shipment_id'])) {
                $order->shipping_gateway_keyword = 'shiprocket';
                $order->tracking_url = 'https://shiprocket.co/tracking/' . $order->order_number;
                $order->save();
            } else {
                Session::flash('warning', 'Shiprocket Error: Order created but no shipment ID was returned. API Response: ' . $responseBody);
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $responseBody = $response ? $response->getBody()->getContents() : '';
            \Log::error('Shiprocket API Bad Response: ' . $responseBody);
            
            $errorMessage = 'Shiprocket Error: ';
            $errorData = json_decode($responseBody, true);
            if (isset($errorData['message'])) {
                $errorMessage .= $errorData['message'];
            }
            if (isset($errorData['errors']) && is_array($errorData['errors'])) {
                $errs = [];
                foreach ($errorData['errors'] as $field => $messages) {
                    if (is_array($messages)) {
                        $errs[] = implode(', ', $messages);
                    } else {
                        $errs[] = $messages;
                    }
                }
                if (!empty($errs)) {
                    $errorMessage .= ' (' . implode('; ', $errs) . ')';
                }
            }
            if ($errorMessage == 'Shiprocket Error: ') {
                $errorMessage .= $e->getMessage();
            }
            
            Session::flash('warning', $errorMessage);
        } catch (\Exception $e) {
            \Log::error('Shiprocket Auto-Create Order Error: ' . $e->getMessage());
            Session::flash('warning', 'Shiprocket Error: ' . $e->getMessage());
        }
    }
}
