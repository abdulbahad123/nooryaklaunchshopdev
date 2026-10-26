<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbAgencyInquiry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        WbAgencyInquiry::ensureTableExists();

        $query = WbAgencyInquiry::query();

        // 1. Search filter
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // 2. Date filter (today, yesterday, custom)
        $dateFilter = $request->input('date_filter');
        if ($dateFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateFilter === 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($dateFilter === 'custom') {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::parse($request->input('start_date'))->startOfDay();
                $end   = Carbon::parse($request->input('end_date'))->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($request->filled('start_date')) {
                $start = Carbon::parse($request->input('start_date'))->startOfDay();
                $query->where('created_at', '>=', $start);
            } elseif ($request->filled('end_date')) {
                $end = Carbon::parse($request->input('end_date'))->endOfDay();
                $query->where('created_at', '<=', $end);
            }
        }

        // Counts for stats cards
        $totalCount     = Schema::hasTable('wb_agency_inquiries') ? WbAgencyInquiry::count() : 0;
        $todayCount     = Schema::hasTable('wb_agency_inquiries') ? WbAgencyInquiry::whereDate('created_at', Carbon::today())->count() : 0;
        $yesterdayCount = Schema::hasTable('wb_agency_inquiries') ? WbAgencyInquiry::whereDate('created_at', Carbon::yesterday())->count() : 0;

        $inquiries = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('website_builder.admin.inquiries.index', compact(
            'inquiries',
            'totalCount',
            'todayCount',
            'yesterdayCount'
        ));
    }

    public function destroy($id)
    {
        WbAgencyInquiry::ensureTableExists();
        $inquiry = WbAgencyInquiry::findOrFail($id);
        $inquiry->delete();

        return redirect()->back()->with('success', 'Contact submission deleted successfully!');
    }
}
