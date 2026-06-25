<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->user->name }} - Resume</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111111;
            line-height: 1.4;
            font-size: 11pt;
            background: #ffffff;
        }
        body.direct-view {
            background: #F4F1EA;
            padding: 0;
            display: block;
            min-height: 100vh;
        }
        .resume-container {
            width: 100%;
            background: #ffffff;
            padding: 15px;
        }
        body.direct-view .resume-container {
            width: 210mm;
            min-height: 297mm;
            margin: 94px auto 40px auto;
            padding: 20mm;
            box-shadow: 0 15px 35px rgba(11, 15, 20, 0.1);
            border: 1px solid #D6CFBE;
            border-radius: 12px;
        }
        @media print {
            body.direct-view {
                background: #ffffff;
                padding: 0;
            }
            body.direct-view .resume-container {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
                border-radius: 0;
            }
            .resume-container {
                padding: 0 !important;
            }
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .header .title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .contact-info {
            font-size: 9.5pt;
            margin-bottom: 5px;
        }
        .contact-info span {
            margin: 0 5px;
        }
        .social-links {
            font-size: 9.5pt;
        }
        .social-links a {
            color: #111111;
            text-decoration: none;
            border-bottom: 1px solid #111111;
        }
        .social-links span {
            margin: 0 5px;
        }
        .section {
            margin-bottom: 18px;
        }
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #111111;
            padding-bottom: 3px;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .summary-text {
            text-align: justify;
            font-size: 10pt;
        }
        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 2px;
            font-size: 10pt;
        }
        .item-sub {
            display: flex;
            justify-content: space-between;
            font-style: italic;
            margin-bottom: 5px;
            font-size: 9.5pt;
        }
        .bullet-list {
            list-style-type: disc;
            margin-left: 20px;
            font-size: 10pt;
        }
        .bullet-list li {
            margin-bottom: 3px;
            text-align: justify;
        }
        .skills-grid {
            font-size: 10pt;
        }
        .skills-row {
            margin-bottom: 4px;
        }
        .skills-label {
            font-weight: bold;
        }
        /* Preview Toolbar */
        .preview-toolbar {
            display: none;
        }
        body.direct-view .preview-toolbar {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 54px;
            background: #0B0F14;
            border-bottom: 1px solid #2B3038;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 9999;
            color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            box-shadow: 0 4px 20px rgba(11, 15, 20, 0.15);
        }
        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .back-btn {
            color: #9AA0AB;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
        }
        .back-btn:hover {
            color: #ffffff;
        }
        .toolbar-divider {
            width: 1px;
            height: 16px;
            background: #2B3038;
        }
        .theme-badge {
            font-size: 10px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .theme-badge.theme-ats {
            background: #ffffff;
            color: #111111;
            border: 1px solid #D6CFBE;
        }
        .toolbar-center {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
        }
        .download-pdf-btn {
            background: #111111;
            color: #ffffff;
            border: 1px solid #2B3038;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .download-pdf-btn:hover {
            background: #222222;
        }
        @media print {
            .preview-toolbar {
                display: none !important;
            }
            body {
                font-size: 10.5pt;
            }
        }
    </style>
</head>
<body>
    <!-- Document Viewer Toolbar -->
    <div class="preview-toolbar">
        <div class="toolbar-left">
            <a href="{{ route('student.analytics') }}" class="back-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Back to Studio</span>
            </a>
            <span class="toolbar-divider"></span>
            <span class="theme-badge theme-ats">ATS Professional</span>
        </div>
        <div class="toolbar-center">
            <span class="document-name">{{ $profile->user->name }} - Resume.pdf</span>
        </div>
        <div class="toolbar-right">
            <button class="download-pdf-btn" onclick="window.print()">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Print / Save PDF</span>
            </button>
        </div>
    </div>
    <div class="resume-container">

    <!-- Header -->
    <div class="header">
        <h1>{{ $profile->user->name }}</h1>
        @if($professionalTitle)
            <div class="title">{{ $professionalTitle }}</div>
        @endif
        
        <div class="contact-info">
            @if($profile->city || $profile->state)
                <span>{{ implode(', ', array_filter([$profile->city, $profile->state, $profile->country])) }}</span>
            @endif
            @if($profile->phone_number)
                <span>•</span> <span>{{ $profile->phone_number }}</span>
            @endif
            <span>•</span> <span>{{ $profile->user->email }}</span>
        </div>

        @if($profile->show_social_links)
            <div class="social-links">
                @php $links = []; @endphp
                @if($profile->github_url)
                    @php $links[] = '<a href="' . $profile->github_url . '" target="_blank">GitHub</a>'; @endphp
                @endif
                @if($profile->linkedin_url)
                    @php $links[] = '<a href="' . $profile->linkedin_url . '" target="_blank">LinkedIn</a>'; @endphp
                @endif
                @if($profile->portfolio_url)
                    @php $links[] = '<a href="' . $profile->portfolio_url . '" target="_blank">Portfolio</a>'; @endphp
                @endif
                @if($profile->leetcode_url)
                    @php $links[] = '<a href="' . $profile->leetcode_url . '" target="_blank">LeetCode</a>'; @endphp
                @endif
                {!! implode(' <span>|</span> ', $links) !!}
            </div>
        @endif
    </div>

    <!-- Summary -->
    <div class="section">
        <div class="section-title">Professional Summary</div>
        <p class="summary-text">{{ $summary }}</p>
    </div>

    <!-- Education -->
    <div class="section">
        <div class="section-title">Education</div>
        <div class="item-header">
            <div>{{ $profile->college_name ?: 'University/College Name' }}</div>
            <div>{{ $profile->graduation_year ? 'Class of ' . $profile->graduation_year : '' }}</div>
        </div>
        <div class="item-sub">
            <div>{{ $profile->degree_name ?: 'Degree Details' }}</div>
            @if($profile->cgpa)
                <div>CGPA: {{ $profile->cgpa }}/10.0</div>
            @endif
        </div>
    </div>

    <!-- Experience -->
    @if(count($experiences) > 0)
        <div class="section">
            <div class="section-title">Verified Experience</div>
            @foreach($experiences as $exp)
                <div style="margin-bottom: 12px;">
                    <div class="item-header">
                        <div>{{ $exp['company_name'] }}</div>
                        <div>{{ $exp['duration'] }}</div>
                    </div>
                    <div class="item-sub">
                        <div>{{ $exp['role'] }}</div>
                    </div>
                    <ul class="bullet-list">
                        @foreach($exp['projects'] as $proj)
                            <li>
                                <strong>{{ ucfirst($proj['title']) }}</strong>: {{ $proj['description'] }}
                                @if($profile->show_ratings && $proj['rating'])
                                    (Rating: {{ $proj['rating'] }}/5)
                                @endif
                                @if($profile->show_stipends && $proj['stipend'])
                                    - Stipend: ₹{{ number_format($proj['stipend']) }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Skills -->
    @if(count($skillsCategorized) > 0)
        <div class="section">
            <div class="section-title">Technical Skills</div>
            <div class="skills-grid">
                @foreach($skillsCategorized as $cat => $list)
                    <div class="skills-row">
                        <span class="skills-label">{{ $cat }}:</span>
                        <span>{{ implode(', ', $list) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Achievements -->
    @if($profile->show_iprs || $profile->show_stipends || $profile->show_ratings)
        <div class="section">
            <div class="section-title">Achievements & Badges</div>
            <ul class="bullet-list">
                @if($profile->show_iprs && $achievements['iprs_score'])
                    <li>IPRS Score: {{ $achievements['iprs_score'] }}/100 ({{ $achievements['iprs_rank'] }})</li>
                @endif
                <li>{{ $achievements['completed_tasks_count'] }} Verified Tasks Completed via InternGrowth marketplace</li>
                @if($profile->show_stipends && $achievements['total_earnings'] > 0)
                    <li>Total Earnings: ₹{{ number_format($achievements['total_earnings']) }} through verified startup projects</li>
                @endif
                @if($profile->show_ratings && $achievements['average_rating'])
                    <li>{{ $achievements['average_rating'] }}/5.0 Average Startup Founder Rating</li>
                @endif
                @if(isset($achievements['badges']) && count($achievements['badges']) > 0)
                    @foreach($achievements['badges'] as $badge)
                        <li>{{ $badge }}</li>
                    @endforeach
                @endif
            </ul>
        </div>
    @endif

    </div>

    <script>
        if (window.self !== window.top) {
            const btn = document.querySelector('.print-btn');
            if (btn) btn.style.display = 'none';
        } else {
            document.body.classList.add('direct-view');
        }
    </script>
</body>
</html>
