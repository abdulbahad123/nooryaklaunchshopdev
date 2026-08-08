<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Membership;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
  public function dashboard()
  {
    $data['incomes'] = Membership::select(DB::raw('MONTH(created_at) month'), DB::raw('sum(price) total'))->where('status', 1)->groupBy('month')->whereYear('created_at', date('Y'))->get();
    $data['users'] = User::join('memberships', 'users.id', '=', 'memberships.user_id')
      ->select(DB::raw('MONTH(users.created_at) month'), DB::raw('count(*) total'))
      ->groupBy('month')
      ->whereYear('users.created_at', date('Y'))
      ->where([
        ['memberships.status', '=', 1],
        ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
        ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
      ])
      ->get();
    $data['defaultLang'] = Language::where('is_default', 1)->first();

    $today = Carbon::now()->toDateString();
    $activeMemberships = Membership::query()
      ->select([
        'ai_engine',
        'ai_token_limit',
        'ai_image_limit',
        'ai_used_tokens',
        'ai_used_images',
        'ai_token_purchased',
        'ai_image_purchased',
      ])
      ->where('status', 1)
      ->where('start_date', '<=', $today)
      ->where('expire_date', '>=', $today)
      ->whereNotNull('ai_engine')
      ->where('ai_engine', '!=', '')
      ->get();

    // $engineStats = [];
    // foreach ($activeMemberships as $membership) {
    //   $engineLabel = trim((string) $membership->ai_engine);
    //   if ($engineLabel === '') {
    //     $engineLabel = 'Unknown';
    //   }
    //   $engineKey = strtolower($engineLabel);

    //   if (!isset($engineStats[$engineKey])) {
    //     $engineStats[$engineKey] = [
    //       'engine' => $engineLabel,
    //       'token_required' => 0,
    //       'token_used' => 0,
    //       'image_required' => 0,
    //       'image_used' => 0,
    //     ];
    //   }

    //   $tokenLimit = max(0, (int) $membership->ai_token_limit);
    //   $tokenPurchased = max(0, (int) $membership->ai_token_purchased);
    //   $tokenUsed = max(0, (int) $membership->ai_used_tokens);
    //   $imageLimit = max(0, (int) $membership->ai_image_limit);
    //   $imagePurchased = max(0, (int) $membership->ai_image_purchased);
    //   $imageUsed = max(0, (int) $membership->ai_used_images);

    //   $engineStats[$engineKey]['token_required'] += $tokenLimit + $tokenPurchased;
    //   $engineStats[$engineKey]['token_used'] += $tokenUsed;
    //   $engineStats[$engineKey]['image_required'] += $imageLimit + $imagePurchased;
    //   $engineStats[$engineKey]['image_used'] += $imageUsed;
    // }

    $engineStats = [];

    foreach ($activeMemberships as $membership) {

      $engineLabel = trim((string) $membership->ai_engine);
      $engineKey = strtolower($engineLabel);

      if (!isset($engineStats[$engineKey])) {
        $engineStats[$engineKey] = [
          'engine' => $engineLabel,
          'token_required' => 0,
          'token_used' => 0,
          'image_required' => 0,
          'image_used' => 0,
        ];
      }

      $tokenLimit      = max(0, (int) $membership->ai_token_limit);
      $tokenPurchased  = max(0, (int) $membership->ai_token_purchased);
      $tokenUsed       = max(0, (int) $membership->ai_used_tokens);

      $imageLimit      = max(0, (int) $membership->ai_image_limit);
      $imagePurchased  = max(0, (int) $membership->ai_image_purchased);
      $imageUsed       = max(0, (int) $membership->ai_used_images);

      $engineStats[$engineKey]['token_required'] += ($tokenLimit + $tokenPurchased);
      $engineStats[$engineKey]['token_used']     += $tokenUsed;

      $engineStats[$engineKey]['image_required'] += ($imageLimit + $imagePurchased);
      $engineStats[$engineKey]['image_used']     += $imageUsed;
    }

    foreach ($engineStats as &$stats) {
      $stats['token_remaining'] = max(0, $stats['token_required'] - $stats['token_used']);
      $stats['image_remaining'] = max(0, $stats['image_required'] - $stats['image_used']);
    }
    unset($stats);

    $data['aiEngineStats'] = array_values($engineStats);
    return view('admin.dashboard', $data);
  }

  public function changeTheme(Request $request)
  {
    return redirect()->back()->withCookie(cookie()->forever('admin-theme', $request->theme));
  }
}
