<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reportIssue()
    {
        return view('support.report-issue');
    }

    public function submitReport(Request $request)
    {
        // Handle report submission
        return redirect()->back()->with('success', 'Report submitted successfully!');
    }
}
