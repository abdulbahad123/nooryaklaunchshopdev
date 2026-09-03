<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\UserCustomDomain;

class WbDomainController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = UserCustomDomain::with('user')->orderBy('id', 'desc');

        if ($status === 'pending') {
            $query->where('status', 0);
        } elseif ($status === 'connected') {
            $query->where('status', 1);
        } elseif ($status === 'rejected') {
            $query->where('status', 2);
        }

        $domains = $query->paginate(15);

        return view('website_builder.admin.domains.index', compact('domains', 'status'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2',
        ]);

        $domain = UserCustomDomain::findOrFail($id);
        $domain->status = (int)$request->status;
        $domain->save();

        $statusText = $domain->status == 1 ? 'Connected' : ($domain->status == 2 ? 'Rejected' : 'Pending');
        return redirect()->back()->with('success', "Domain request updated to {$statusText}.");
    }
}
