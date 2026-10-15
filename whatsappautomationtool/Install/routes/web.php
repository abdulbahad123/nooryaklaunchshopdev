<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/admin', function () {
    return redirect('/admin/dashboard');
});
