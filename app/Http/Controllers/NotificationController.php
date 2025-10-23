<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display the notifications page
     */
    public function index()
    {
        return view('notifications');
    }
}
