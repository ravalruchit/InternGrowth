<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->user->name }} - Resume</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            color: #2D3748;
            line-height: 1.5;
            font-size: 10pt;
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
            margin-bottom: 25px;
            border-bottom: 2px solid #E2E8F0;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 26pt;
            font-weight: 800;
            color: #4F46E5; /* Indigo accent */
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }
        .header .title {
            font-size: 13pt;
            font-weight: 600;
            color: #4B5563;
            margin-bottom: 12px;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 8px;
            font-size: 8.5pt;
            color: #6B7280;
        }
        .contact-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .contact-item a {
            color: #4F46E5;
            text-decoration: none;
        }
        .contact-item a:hover {
            text-decoration: underline;
        }
        .section {
            margin-bottom: 22px;
        }
        .section-title {
            font-size: 11pt;
            font-weight: 700;
            color: #1F2937;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background: #E2E8F0;
        }
        .summary-text {
            color: #4B5563;
            font-size: 9.5pt;
            text-align: justify;
        }
        .education-card {
            background: #F8FAFC;
            border-left: 3px solid #4F46E5;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 10px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
        }
        .card-sub {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            color: #6B7280;
        }
        .experience-item {
            margin-bottom: 15px;
        }
        .exp-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
        }
        .company-name {
            font-weight: 700;
            color: #1F2937;
            font-size: 10.5pt;
        }
        .duration {
            font-size: 8.5pt;
            color: #9CA3AF;
            font-weight: 500;
        }
        .exp-role {
            font-weight: 600;
            color: #4F46E5;
            font-size: 9pt;
            margin-bottom: 8px;
        }
        .project-bullet {
            margin-bottom: 6px;
            padding-left: 15px;
            position: relative;
            font-size: 9pt;
            color: #4B5563;
        }
        .project-bullet::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #4F46E5;
            font-weight: bold;
        }
        .project-meta {
            display: inline-flex;
            gap: 8px;
            margin-top: 2px;
            font-size: 8pt;
        }
        .badge {
            background: #EEF2F6;
            color: #475569;
            padding: 1px 6px;
            border-radius: 4px;
            font-weight: 500;
        }
        .badge.verified {
            background: #DCFCE7;
            color: #15803D;
        }
        .badge.rating {
            background: #FEF3C7;
            color: #D97706;
        }
        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .skill-category {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 10px 14px;
            flex: 1 1 calc(50% - 12px);
            min-width: 200px;
        }
        .skill-cat-title {
            font-weight: 700;
            font-size: 8.5pt;
            color: #4F46E5;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .skill-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .skill-pill {
            background: #FFFFFF;
            border: 1px solid #CBD5E1;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 8pt;
            color: #334155;
            font-weight: 500;
        }
        .achievement-pills {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 10px;
        }
        .achievement-card {
            background: #EEF2F6;
            border-radius: 8px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 8.5pt;
            color: #334155;
            font-weight: 600;
        }
        .achievement-card span.icon {
            font-size: 14pt;
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
            font-family: 'Inter', sans-serif;
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
        .theme-badge.theme-startup {
            background: #EEF2F6;
            color: #4F46E5;
            border: 1px solid #CBD5E1;
        }
        .toolbar-center {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
        }
        .download-pdf-btn {
            background: #4F46E5;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }
        .download-pdf-btn:hover {
            background: #4338CA;
            transform: translateY(-1px);
        }
        .download-pdf-btn:active {
            transform: translateY(0);
        }
        @media print {
            .preview-toolbar {
                display: none !important;
            }
            body {
                padding: 0;
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
            <span class="theme-badge theme-startup">Startup Modern</span>
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
        
        <div class="contact-grid">
            @if($profile->city || $profile->state)
                <div class="contact-item">
                    <span>📍</span>
                    <span>{{ implode(', ', array_filter([$profile->city, $profile->state, $profile->country])) }}</span>
                </div>
            @endif
            @if($profile->phone_number)
                <div class="contact-item">
                    <span>📞</span>
                    <span>{{ $profile->phone_number }}</span>
                </div>
            @endif
            <div class="contact-item">
                <span>✉️</span>
                <span>{{ $profile->user->email }}</span>
            </div>
            
            @if($profile->show_social_links)
                @if($profile->github_url)
                    <div class="contact-item">
                        <span>💻</span>
                        <a href="{{ $profile->github_url }}" target="_blank">github.com/{{ basename($profile->github_url) }}</a>
                    </div>
                @endif
                @if($profile->linkedin_url)
                    <div class="contact-item">
                        <span>💼</span>
                        <a href="{{ $profile->linkedin_url }}" target="_blank">linkedin.com/in/{{ basename($profile->linkedin_url) }}</a>
                    </div>
                @endif
                @if($profile->portfolio_url)
                    <div class="contact-item">
                        <span>🌐</span>
                        <a href="{{ $profile->portfolio_url }}" target="_blank">portfolio</a>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Summary -->
    <div class="section">
        <div class="section-title">Summary</div>
        <p class="summary-text">{{ $summary }}</p>
    </div>

    <!-- Education -->
    <div class="section">
        <div class="section-title">Education</div>
        <div class="education-card">
            <div class="card-header">
                <div>{{ $profile->college_name ?: 'University/College Name' }}</div>
                <div>{{ $profile->graduation_year ? 'Class of ' . $profile->graduation_year : '' }}</div>
            </div>
            <div class="card-sub">
                <div>{{ $profile->degree_name ?: 'Degree / Course details' }}</div>
                @if($profile->cgpa)
                    <div>CGPA: <strong>{{ $profile->cgpa }}/10.0</strong></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Experience -->
    @if(count($experiences) > 0)
        <div class="section">
            <div class="section-title">Verified Experience</div>
            @foreach($experiences as $exp)
                <div class="experience-item">
                    <div class="exp-header">
                        <span class="company-name">{{ $exp['company_name'] }}</span>
                        <span class="duration">{{ $exp['duration'] }}</span>
                    </div>
                    <div class="exp-role">{{ $exp['role'] }}</div>
                    
                    @foreach($exp['projects'] as $proj)
                        <div class="project-bullet">
                            <strong>{{ ucfirst($proj['title']) }}</strong> — {{ $proj['description'] }}
                            <div class="project-meta">
                                <span class="badge verified">✓ Verified</span>
                                @if($profile->show_ratings && $proj['rating'])
                                    <span class="badge rating">★ {{ $proj['rating'] }}/5</span>
                                @endif
                                @if($profile->show_stipends && $proj['stipend'])
                                    <span class="badge">₹{{ number_format($proj['stipend']) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif

    <!-- Skills -->
    @if(count($skillsCategorized) > 0)
        <div class="section">
            <div class="section-title">Skills & Tech Stack</div>
            <div class="skills-container">
                @foreach($skillsCategorized as $cat => $list)
                    <div class="skill-category">
                        <div class="skill-cat-title">{{ $cat }}</div>
                        <div class="skill-pills">
                            @foreach($list as $sk)
                                <span class="skill-pill">{{ $sk }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Achievements -->
    @if($profile->show_iprs || $profile->show_stipends || $profile->show_ratings)
        <div class="section">
            <div class="section-title">Achievements</div>
            <div class="achievement-pills">
                @if($profile->show_iprs && $achievements['iprs_score'])
                    <div class="achievement-card">
                        <span class="icon">🏆</span>
                        <div>{{ $achievements['iprs_rank'] }} (Score: {{ $achievements['iprs_score'] }})</div>
                    </div>
                @endif
                <div class="achievement-card">
                    <span class="icon">🚀</span>
                    <div>{{ $achievements['completed_tasks_count'] }} Verified Projects Completed</div>
                </div>
                @if($profile->show_stipends && $achievements['total_earnings'] > 0)
                    <div class="achievement-card">
                        <span class="icon">💰</span>
                        <div>₹{{ number_format($achievements['total_earnings']) }} Total Earnings</div>
                    </div>
                @endif
                @if($profile->show_ratings && $achievements['average_rating'])
                    <div class="achievement-card">
                        <span class="icon">⭐</span>
                        <div>{{ $achievements['average_rating'] }}/5.0 Startup Rating</div>
                    </div>
                @endif
            </div>
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
