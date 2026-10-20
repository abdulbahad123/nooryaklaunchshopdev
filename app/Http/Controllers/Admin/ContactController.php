<?php

namespace App\Http\Controllers\Admin;

use App\Models\BasicExtended;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BasicSetting;
use App\Models\Language;
use App\Models\AdminContactMessage;
use Carbon\Carbon;
use Session;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        // 1. Language & Settings
        if (empty($request->language)) {
            $data['lang_id'] = 0;
            $data['abs'] = BasicSetting::first() ?? new BasicSetting();
            $data['abe'] = BasicExtended::first() ?? new BasicExtended();
        } else {
            $lang = Language::where('code', $request->language)->firstOrFail();
            $data['lang_id'] = $lang->id;
            $data['abs'] = $lang->basic_setting ?? BasicSetting::first();
            $data['abe'] = $lang->basic_extended ?? BasicExtended::first();
        }

        // 2. Ensure table exists & floating columns exist
        AdminContactMessage::ensureTableExists();
        \App\Models\Admin\ImageText::ensureColumnsExist();
        $targetLid = !empty($data['lang_id']) ? $data['lang_id'] : (Language::where('is_default', 1)->value('id') ?? 1);
        $data['homeSec'] = \App\Models\Admin\ImageText::where('language_id', $targetLid)->first() ?? \App\Models\Admin\ImageText::first() ?? new \App\Models\Admin\ImageText();

        // 3. Query Submitted Form Leads
        $query = AdminContactMessage::query();

        // Date Filter
        $dateFilter = $request->input('date_filter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($dateFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateFilter === 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($dateFilter === 'this_week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($dateFilter === 'this_month') {
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($dateFilter === 'custom' || (!empty($startDate) && !empty($endDate))) {
            try {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            } catch (\Throwable $e) {}
        }

        // Search Term (Name, Email, Phone, Subject)
        if ($request->filled('term')) {
            $term = trim($request->term);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('subject', 'like', "%{$term}%")
                    ->orWhere('describe_issues', 'like', "%{$term}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && in_array($request->status, ['unread', 'read'])) {
            $query->where('status', $request->status);
        }

        $data['messages'] = $query->orderBy('id', 'DESC')->paginate(15);

        // Stats
        try {
            $data['total_count'] = AdminContactMessage::count();
            $data['today_count'] = AdminContactMessage::whereDate('created_at', Carbon::today())->count();
            $data['unread_count'] = AdminContactMessage::where('status', 'unread')->count();
        } catch (\Throwable $e) {
            $data['total_count'] = 0;
            $data['today_count'] = 0;
            $data['unread_count'] = 0;
        }

        $data['active_tab'] = $request->input('tab', 'settings');

        return view('admin.contact', $data);
    }

    public function update(Request $request, $langid)
    {
        $request->validate([
            'contact_addresses' => 'required',
            'contact_numbers' => 'required',
            'contact_mails' => 'required'
        ]);

        // 1. Update Contact Information in BasicExtended
        if ($langid == 0) {
            $bes = BasicExtended::all();
            foreach ($bes as $be) {
                $be->contact_addresses = $request->contact_addresses;
                $be->contact_numbers = $request->contact_numbers;
                $be->contact_mails = $request->contact_mails;
                $be->save();
            }
        } else {
            $be = BasicExtended::where('language_id', $langid)->first();
            if (!$be) {
                $be = BasicExtended::first();
            }
            if (!$be) {
                $be = new BasicExtended();
                $be->language_id = $langid;
            }
            $be->contact_addresses = $request->contact_addresses;
            $be->contact_numbers = $request->contact_numbers;
            $be->contact_mails = $request->contact_mails;
            $be->save();
        }

        // 2. Update Google reCAPTCHA in BasicSetting
        $bss = BasicSetting::all();
        foreach ($bss as $bs) {
            $bs->is_recaptcha = $request->is_recaptcha == 1 ? 1 : 0;
            $bs->google_recaptcha_site_key = $request->google_recaptcha_site_key;
            $bs->google_recaptcha_secret_key = $request->google_recaptcha_secret_key;
            $bs->save();
        }

        Session::flash('success', __('Contact Information & Google reCAPTCHA updated successfully'));
        return redirect()->to(route('admin.contact.index', ['language' => request()->input('language')]) . '#tab-settings');
    }

    public function deleteMessage($id)
    {
        AdminContactMessage::ensureTableExists();
        $message = AdminContactMessage::findOrFail($id);
        $message->delete();

        Session::flash('success', __('Contact lead deleted successfully'));
        return redirect()->to(route('admin.contact.index') . '#tab-messages');
    }

    public function bulkDeleteMessages(Request $request)
    {
        AdminContactMessage::ensureTableExists();
        $ids = $request->ids;
        if (!empty($ids) && is_array($ids)) {
            AdminContactMessage::whereIn('id', $ids)->delete();
            Session::flash('success', __('Selected leads deleted successfully'));
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error', 'message' => 'No items selected'], 400);
    }

    public function viewMessage($id)
    {
        AdminContactMessage::ensureTableExists();
        $message = AdminContactMessage::findOrFail($id);
        
        // Mark as read
        if ($message->status !== 'read') {
            $message->status = 'read';
            $message->save();
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'phone' => $message->phone,
                'describe_issues' => $message->describe_issues,
                'subject' => $message->subject,
                'message' => $message->message,
                'status' => $message->status,
                'created_at' => $message->created_at ? $message->created_at->format('M d, Y h:i A') : '-',
                'time_ago' => $message->created_at ? $message->created_at->diffForHumans() : '-'
            ]
        ]);
    }

    public function updateFloatingButtons(Request $request)
    {
        \App\Models\Admin\ImageText::ensureColumnsExist();

        $fields = [
            'floating_call_status',
            'floating_call_number',
            'floating_call_label',
            'floating_whatsapp_status',
            'floating_whatsapp_number',
            'floating_whatsapp_header_title',
            'floating_whatsapp_online_status',
            'floating_whatsapp_welcome_msg',
            'floating_whatsapp_default_send_msg',
            'floating_whatsapp_prompt1_text',
            'floating_whatsapp_prompt1_url',
            'floating_whatsapp_prompt2_text',
            'floating_whatsapp_prompt2_msg',
            'floating_whatsapp_prompt3_text',
            'floating_whatsapp_prompt3_url',
            // Contact Page Support Card (Issue 2)
            'contact_support_title',
            'contact_support_subtitle',
            'contact_support_btn_text',
            'contact_support_btn_number',
            'contact_support_btn_msg',
        ];

        $data = [];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        $langCode = $request->input('language_code', 'en');
        $lang = Language::where('code', $langCode)->first() ?? Language::first();
        $targetLangId = $lang ? $lang->id : 1;

        try {
            $content = \App\Models\Admin\ImageText::where('language_id', $targetLangId)->first();
            if ($content) {
                $content->update($data);
            } else {
                $data['unique_id'] = 12345;
                $data['language_id'] = $targetLangId;
                \App\Models\Admin\ImageText::create($data);
            }
            \App\Models\Admin\ImageText::where('language_id', $targetLangId)->update($data);
            // Also sync floating phone numbers across all image_texts so site-wide floating button works
            $globalFloatingData = array_intersect_key($data, array_flip([
                'floating_call_status', 'floating_call_number', 'floating_call_label',
                'floating_whatsapp_status', 'floating_whatsapp_number', 'floating_whatsapp_header_title',
                'floating_whatsapp_online_status', 'floating_whatsapp_welcome_msg', 'floating_whatsapp_default_send_msg'
            ]));
            if (!empty($globalFloatingData)) {
                \App\Models\Admin\ImageText::query()->update($globalFloatingData);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            \App\Models\Admin\ImageText::ensureColumnsExist();
            try {
                \App\Models\Admin\ImageText::where('language_id', $targetLangId)->update($data);
            } catch (\Throwable $ex) {}
        }

        // Sync with BasicSetting and BasicExtended
        if ($request->filled('floating_whatsapp_number')) {
            \App\Models\BasicSetting::query()->update([
                'whatsapp_number' => $request->input('floating_whatsapp_number'),
                'whatsapp_header_title' => $request->input('floating_whatsapp_header_title'),
                'is_whatsapp' => $request->input('floating_whatsapp_status', 1),
            ]);
        }
        if ($request->filled('floating_call_number')) {
            $bes = \App\Models\BasicExtended::all();
            foreach ($bes as $be) {
                $numbers = array_filter(array_map('trim', explode(',', $be->contact_numbers ?? '')));
                $newCallNum = trim($request->input('floating_call_number'));
                if (empty($numbers)) {
                    $be->contact_numbers = $newCallNum;
                } else {
                    $numbers[0] = $newCallNum;
                    $be->contact_numbers = implode(',', $numbers);
                }
                $be->save();
            }
        }

        Session::flash('success', __('Contact floating button settings updated successfully!'));
        $langCode = $request->input('language_code', 'en');
        return redirect()->to(route('admin.contact.index', ['language' => $langCode]) . '#tab-floating');
    }
}