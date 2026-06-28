<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\StartupProfile;
use App\Models\Skill;
use Illuminate\Support\Str;

class StartupOnboardingController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        if ($user->is_onboarded) {
            return redirect()->route('startup.dashboard');
        }

        $profile = $user->startupProfile;
        $skills = Skill::limit(20)->get();

        return view('startup.onboarding', compact('profile', 'skills'));
    }

    public function step1(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
        ]);

        $profile = auth()->user()->startupProfile;
        $profile->update([
            'company_name' => $request->company_name,
            'industry' => $request->industry,
        ]);

        return response()->json(['success' => true]);
    }

    public function step2(Request $request)
    {
        $user = auth()->user();
        $profile = $user->startupProfile;

        if ($request->has('skip') && $request->skip) {
            return response()->json(['success' => true]);
        }

        $request->validate([
            'registration_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('registration_doc')) {
            $path = $request->file('registration_doc')->store('startup_docs', 'public');
            
            \App\Models\StartupVerificationLog::create([
                'startup_profile_id' => $profile->id,
                'document_path' => $path,
                'status' => 'pending',
                'notes' => 'Onboarding document submission.',
            ]);

            $profile->update([
                'is_verified' => false,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function step3(Request $request)
    {
        $user = auth()->user();
        $profile = $user->startupProfile;

        if ($request->has('skip') && $request->skip) {
            $user->update(['is_onboarded' => true]);
            return response()->json(['success' => true, 'redirect' => route('startup.dashboard')]);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'stipend' => 'required|numeric|min:0',
            'skills' => 'required|array',
            'skills.*' => 'integer|exists:skills,id',
        ]);

        $task = Task::create([
            'startup_profile_id' => $profile->id,
            'title' => $request->title,
            'description' => $request->description,
            'stipend' => $request->stipend,
            'status' => 'posted',
        ]);

        $task->skills()->sync($request->skills);

        $user->update(['is_onboarded' => true]);

        return response()->json(['success' => true, 'redirect' => route('startup.dashboard')]);
    }
}
