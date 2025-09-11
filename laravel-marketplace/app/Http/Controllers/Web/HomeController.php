<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Display the pricing page
     */
    public function pricing()
    {
        // Get subscription plans for pricing page
        $plans = \App\Models\Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pricing', compact('plans'));
    }
}
