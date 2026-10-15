<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/admin', function () {
    if (Auth::check() && (int)Auth::user()->role === 2) {
        session(['login_as' => 'admin']);
        return redirect('/admin/dashboard');
    }
    return redirect('/auth/login');
});

Route::get('/auth/login-as-admin', function () {
    if (Auth::check() && (int)Auth::user()->role === 2) {
        session(['login_as' => 'admin']);
        return redirect('/admin/dashboard');
    }
    return redirect('/auth/login');
});

Route::get('/auth/login-as-user', function () {
    if (Auth::check() && (int)Auth::user()->role === 2) {
        session(['login_as' => 'client']);
        return redirect('/app/dashboard');
    }
    return redirect('/auth/login');
});
