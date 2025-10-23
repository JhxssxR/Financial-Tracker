<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the profile page
     */
    public function index()
    {
        return view('profile');
    }
}
