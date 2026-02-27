<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;

class LoginLogController extends Controller
{
    public function index()
    {
        $loginLogs = LoginLog::with(['user', 'device'])
            ->latest()
            ->paginate(20);
        return view("loginLogs.index", ['loginLogs' => $loginLogs]);
    }
}
