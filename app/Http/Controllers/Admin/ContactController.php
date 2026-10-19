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

        // 2. Ensure table exists
        AdminContactMessage::ensureTableExists();

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
            $be = BasicExtended::where('language_id', $langid)->firstOrFail();
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
}