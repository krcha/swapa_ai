<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function termsOfService()
    {
        return view('legal.terms-of-service');
    }

    public function privacyPolicy()
    {
        return view('legal.privacy-policy');
    }

    public function cookiePolicy()
    {
        return view('legal.cookie-policy');
    }
}
