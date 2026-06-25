@php
    $theme = request('theme', $profile->resume_theme ?: 'ats');
@endphp

@if($theme === 'ats')
    @include('student.resumes.ats')
@elseif($theme === 'startup')
    @include('student.resumes.startup')
@elseif($theme === 'verified')
    @include('student.resumes.verified')
@elseif($theme === 'developer')
    @include('student.resumes.developer')
@else
    @include('student.resumes.ats')
@endif
