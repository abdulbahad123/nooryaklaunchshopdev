<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class LoginController extends Controller
{
    public function login()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admins') && Auth::guard('admin')->check()) {
                return $this->redirectToWbDashboard();
            }
        } catch (\Throwable $e) {}

        return view('website_builder.admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usernameInput = trim($request->username);
        $passwordInput = $request->password;

        try {
            // 1. Direct attempt on admin guard using username, email, or first_name
            if (\Illuminate\Support\Facades\Schema::hasTable('admins')) {
                if (
                    Auth::guard('admin')->attempt(['username' => $usernameInput, 'password' => $passwordInput]) ||
                    Auth::guard('admin')->attempt(['email' => $usernameInput, 'password' => $passwordInput]) ||
                    Auth::guard('admin')->attempt(['first_name' => $usernameInput, 'password' => $passwordInput])
                ) {
                    $request->session()->regenerate();
                    return $this->redirectToWbDashboard();
                }
            }

            // 2. Direct lookup in wb_staff table (matches against email or full staff name)
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_staff')) {
                $staff = \App\Models\WebsiteBuilder\WbStaff::where('email', $usernameInput)
                    ->orWhere('name', $usernameInput)
                    ->first();

                if ($staff) {
                    $passwordValid = \Illuminate\Support\Facades\Hash::check($passwordInput, $staff->password) || ($passwordInput === $staff->password);

                    if ($passwordValid) {
                        if (isset($staff->is_active) && !$staff->is_active) {
                            return redirect()->back()->with('alert', __('This staff account has been deactivated. Please contact the administrator.'));
                        }

                        // Upgrade plain-text password to bcrypt if needed
                        if ($passwordInput === $staff->password) {
                            $staff->password = \Illuminate\Support\Facades\Hash::make($passwordInput);
                            $staff->save();
                        }

                        // Ensure corresponding Admin record in admins table exists and has status = 1
                        if (\Illuminate\Support\Facades\Schema::hasTable('admins')) {
                            $adminUser = Admin::where('email', $staff->email)
                                ->orWhere('username', $staff->email)
                                ->orWhere('username', $usernameInput)
                                ->first();

                            $role = null;
                            if (\Illuminate\Support\Facades\Schema::hasTable('roles')) {
                                $role = \App\Models\Role::where('name', $staff->role)->first();
                            }
                            $roleId = $role ? $role->id : null;

                            if (!$adminUser) {
                                $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode('@', $staff->email)[0]));
                                if (empty($baseUsername)) $baseUsername = 'staff';
                                $uname = $baseUsername;
                                $counter = 1;
                                while (Admin::where('username', $uname)->exists()) {
                                    $uname = $baseUsername . $counter;
                                    $counter++;
                                }

                                $adminUser = Admin::create([
                                    'username'   => $uname,
                                    'email'      => $staff->email,
                                    'first_name' => $staff->name,
                                    'last_name'  => '',
                                    'password'   => $staff->password,
                                    'role_id'    => $roleId,
                                    'status'     => 1,
                                ]);
                            } else {
                                $adminUser->password = $staff->password;
                                if ($roleId) {
                                    $adminUser->role_id = $roleId;
                                }
                                $adminUser->status = 1;
                                $adminUser->save();
                            }

                            Auth::guard('admin')->login($adminUser);
                            $request->session()->regenerate();
                            return $this->redirectToWbDashboard();
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Website Builder Admin Login Error: " . $e->getMessage());
        }

        return redirect()->back()->with('alert', __('Invalid username/email or password credentials.'));
    }

    /**
     * Helper to reliably redirect to the Website Builder admin dashboard
     */
    protected function redirectToWbDashboard()
    {
        if (\Illuminate\Support\Facades\Route::has('website-builder.admin.dashboard')) {
            return redirect()->intended(route('website-builder.admin.dashboard'));
        }
        return redirect()->intended(url('/website-builder/admin/dashboard'));
    }

    public function autoLogin(Request $request)
    {
        abort(404);
    }

    /**
     * Helper to auto-import clean template if core base tables are missing.
     */
    protected function ensureBaseSchemaAndAdmin(): void
    {
        try {
            $hasAdmins = \Illuminate\Support\Facades\Schema::hasTable('admins');
            $hasSettings = \Illuminate\Support\Facades\Schema::hasTable('basic_settings');
            if (!$hasAdmins || !$hasSettings) {
                $paths = [
                    database_path("schema/website_builder_clean_template.sql"),
                    base_path("../Sass_admin/database/schema/website_builder_clean_template.sql"),
                ];
                $schemaFile = null;
                foreach ($paths as $p) {
                    if (file_exists($p)) {
                        $schemaFile = $p;
                        break;
                    }
                }
                if ($schemaFile) {
                    $pdo = \Illuminate\Support\Facades\DB::connection('mysql')->getPdo();
                    $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
                    $sql = file_get_contents($schemaFile);
                    $statements = preg_split('/;\s*[\r\n]+/', $sql);
                    foreach ($statements as $stmt) {
                        $stmt = trim($stmt);
                        if (!empty($stmt)) {
                            try { $pdo->exec($stmt); } catch (\Throwable $ex) {}
                        }
                    }
                    $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
                }
            }
        } catch (\Throwable $ex) {
            \Illuminate\Support\Facades\Log::warning("LoginController ensureBaseSchemaAndAdmin failed: " . $ex->getMessage());
        }
    }

    public function ssoLogin(Request $request)
    {
        $user = $request->query('user');
        $expires = $request->query('expires');
        $nonce = $request->query('nonce');
        $signature = $request->query('signature');

        if (!$user || !$expires || !$nonce || !$signature) {
            return redirect()->to(url('/admin/login'))->with('alert', __('Invalid SSO parameters.'));
        }

        if (time() > (int)$expires) {
            return redirect()->to(url('/admin/login'))->with('alert', __('SSO link expired. Please try auto-login again.'));
        }

        $secret = env('SSO_SECRET_KEY');
        if (empty($secret)) {
            return redirect()->to(url('/admin/login'))->with('alert', __('SSO authentication is temporarily unavailable.'));
        }
        $expectedSignature = hash_hmac('sha256', "{$user}|{$expires}|{$nonce}", $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            return redirect()->to(url('/admin/login'))->with('alert', __('SSO signature verification failed.'));
        }

        $admin = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admins')) {
                $admin = Admin::where('username', $user)->orWhere('email', $user)->first();
            }
        } catch (\Throwable $e) {}

        if ($admin) {
            Auth::guard('admin')->login($admin);
        } else {
            session(['wb_super_admin_authenticated' => true]);
        }

        $request->session()->regenerate();
        return $this->redirectToWbDashboard();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('website-builder.admin.login');
    }
}
