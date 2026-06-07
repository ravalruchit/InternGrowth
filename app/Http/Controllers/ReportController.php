<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function show($type = null, $id = null)
    {
        return view('report.create', compact('type', 'id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:user,task,submission,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'reported_id' => 'nullable|integer',
        ]);

        $reporter = auth()->user();
        
        // Send email to admin
        try {
            Mail::send('emails.report', [
                'reporter' => $reporter,
                'reportType' => $validated['report_type'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'reportedId' => $validated['reported_id'] ?? null,
                'reportedAt' => now(),
            ], function($message) {
                $message->to('ravalruchit@gmail.com');
                $message->subject('New Report - InternGrowth Platform');
            });

            return redirect()->back()->with('success', 'Report submitted successfully. We will review it shortly.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to submit report. Please try again.');
        }
    }
}
