<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\StudentProfile;

class StudentOnboardingController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        if ($user->is_onboarded) {
            return redirect()->route('student.dashboard');
        }

        $profile = $user->studentProfile;
        $domains = StudentProfile::$domains;
        
        // Fetch or seed default skills for selection
        $skills = Skill::whereIn('name', ['Laravel', 'React', 'Flutter', 'Python', 'Java', 'UI/UX', 'NodeJS', 'DevOps'])->get();
        if ($skills->isEmpty()) {
            $defaultSkills = ['Laravel', 'React', 'Flutter', 'Python', 'Java', 'UI/UX', 'NodeJS', 'DevOps'];
            foreach ($defaultSkills as $skName) {
                Skill::firstOrCreate(['name' => $skName], ['domain' => 'Software Development']);
            }
            $skills = Skill::whereIn('name', $defaultSkills)->get();
        }

        return view('student.onboarding', compact('profile', 'domains', 'skills'));
    }

    public function step1(Request $request)
    {
        $request->validate([
            'college_name' => 'required|string|max:255',
            'graduation_year' => 'required|integer|min:2020|max:2035',
            'primary_domain' => 'required|string|max:255',
        ]);

        $profile = auth()->user()->studentProfile;
        $profile->update([
            'college_name' => $request->college_name,
            'graduation_year' => $request->graduation_year,
            'primary_domain' => $request->primary_domain,
            'preferred_role' => $profile->preferred_role ?: (StudentProfile::$domains[$request->primary_domain][0] ?? 'Intern'),
        ]);

        return response()->json(['success' => true]);
    }

    public function step2(Request $request)
    {
        $request->validate([
            'skills' => 'required|array',
            'skills.*' => 'integer|exists:skills,id',
        ]);

        $profile = auth()->user()->studentProfile;
        $profile->skills()->sync($request->skills);

        return response()->json(['success' => true]);
    }

    public function step3(Request $request)
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        if ($request->has('skip') && $request->skip) {
            $user->update(['is_onboarded' => true]);
            return response()->json(['success' => true, 'redirect' => route('student.dashboard')]);
        }

        $request->validate([
            'college_email' => 'nullable|email|max:255',
            'id_card' => 'nullable|image|max:5120',
        ]);

        if ($request->college_email) {
            $profile->update([
                'college_email' => $request->college_email,
                'verification_method' => 'email',
                'is_verified' => false,
            ]);
        } elseif ($request->hasFile('id_card')) {
            $path = $request->file('id_card')->store('id_cards', 'public');
            $profile->update([
                'id_card_path' => $path,
                'verification_method' => 'id_card',
                'id_card_verification_status' => 'pending',
                'id_card_submitted_at' => now(),
            ]);
        }

        $user->update(['is_onboarded' => true]);

        return response()->json(['success' => true, 'redirect' => route('student.dashboard')]);
    }
}
