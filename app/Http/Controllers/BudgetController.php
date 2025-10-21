<?php

namespace App\Http\Controllers;

class BudgetController extends Controller
{
    public function index()
    {
        return view('budgets');
    }
}
