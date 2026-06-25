<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->user->name }} - Verified Profile</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            color: #1E293B;
            line-height: 1.45;
            font-size: 9.5pt;
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
        .verified-badge-top {
            background: #0F172A;
            color: #10B981;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 8.5pt;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header {
            margin-bottom: 22px;
        }
        .header h1 {
            font-size: 24pt;
            font-weight: 900;
            color: #0F172A;
            margin-bottom: 3px;
        }
        .header .title {
            font-size: 12pt;
            font-weight: 600;
            color: #64748B;
            margin-bottom: 12px;
        }
        .contact-info {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 8.5pt;
            color: #475569;
        }
        .contact-info span {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .contact-info a {
            color: #0F172A;
            text-decoration: none;
            font-weight: 600;
        }
        
        /* The Trust Metric Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 25px;
            background: #F8FAFC;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            padding: 16px;
        }
        .metric-card {
            text-align: center;
            border-right: 1px solid #E2E8F0;
        }
        .metric-card:last-child {
            border-right: none;
        }
        .metric-val {
            font-size: 18pt;
            font-weight: 900;
            color: #0F172A;
        }
        .metric-val.green {
            color: #10B981;
        }
        .metric-label {
            font-size: 7.5pt;
            color: #64748B;
            text-transform: uppercase;
            font-weight: 700;
            margin-top: 2px;
            letter-spacing: 0.5px;
        }

        .section {
            margin-bottom: 22px;
        }
        .section-title {
            font-size: 10.5pt;
            font-weight: 800;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            padding-bottom: 4px;
            border-bottom: 2px solid #0F172A;
        }
        .summary-text {
            color: #334155;
            font-size: 9pt;
            text-align: justify;
        }
        
        /* Experiences & Timeline */
        .experience-item {
            margin-bottom: 16px;
            border-left: 2px solid #E2E8F0;
            padding-left: 15px;
            position: relative;
        }
        .experience-item::before {
            content: "";
            position: absolute;
            left: -5px;
            top: 2px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10B981;
        }
        .exp-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
        }
        .company-name {
            font-weight: 800;
            color: #0F172A;
            font-size: 10pt;
        }
        .duration {
            font-size: 8pt;
            color: #64748B;
            font-weight: 600;
        }
        .exp-role {
            font-weight: 600;
            color: #10B981;
            font-size: 8.5pt;
            margin-bottom: 6px;
        }
        .project-row {
            margin-bottom: 8px;
        }
        .project-title {
            font-size: 9pt;
            font-weight: 700;
            color: #334155;
        }
        .project-desc {
            font-size: 8.5pt;
            color: #475569;
            margin-top: 1px;
        }
        .project-tags {
            display: flex;
            gap: 6px;
            margin-top: 3px;
        }
        .tag {
            background: #ECFDF5;
            color: #065F46;
            font-size: 7.5pt;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
        }
        .tag.rating {
            background: #FFFBEB;
            color: #92400E;
        }

        /* Education & Skills Grid */
        .grid-half {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 22px;
        }
        .edu-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 12px;
        }
        .edu-title {
            font-weight: 800;
            color: #0F172A;
            font-size: 9pt;
        }
        .edu-sub {
            font-size: 8.5pt;
            color: #475569;
            margin-top: 4px;
        }
        .edu-meta {
            font-size: 8pt;
            color: #64748B;
            margin-top: 2px;
        }

        /* Skills Pill list */
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        .skill-pill {
            background: #F1F5F9;
            color: #334155;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 8pt;
            font-weight: 600;
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
        .theme-badge.theme-verified {
            background: #0F172A;
            color: #10B981;
            border: 1px solid #1E293B;
        }
        .toolbar-center {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
        }
        .download-pdf-btn {
            background: #10B981;
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
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        .download-pdf-btn:hover {
            background: #059669;
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
            <span class="theme-badge theme-verified">Verified Candidate</span>
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

    <!-- Top Verification Header -->
    <div class="verified-badge-top">
        <span>🛡️</span> InternGrowth Verified Candidate Profile
    </div>

    <!-- Main Header -->
    <div class="header">
        <h1>{{ $profile->user->name }}</h1>
        @if($professionalTitle)
            <div class="title">{{ $professionalTitle }}</div>
        @endif
        
        <div class="contact-info">
            @if($profile->city || $profile->state)
                <span>📍 {{ implode(', ', array_filter([$profile->city, $profile->state, $profile->country])) }}</span>
            @endif
            @if($profile->phone_number)
                <span>📞 {{ $profile->phone_number }}</span>
            @endif
            <span>✉️ {{ $profile->user->email }}</span>
            
            @if($profile->show_social_links)
                @if($profile->github_url)
                    <span>💻 <a href="{{ $profile->github_url }}" target="_blank">GitHub</a></span>
                @endif
                @if($profile->linkedin_url)
                    <span>💼 <a href="{{ $profile->linkedin_url }}" target="_blank">LinkedIn</a></span>
                @endif
                @if($profile->portfolio_url)
                    <span>🌐 <a href="{{ $profile->portfolio_url }}" target="_blank">Portfolio</a></span>
                @endif
            @endif
        </div>
    </div>

    <!-- Dynamic Metrics Panel -->
    @if($profile->show_iprs || $profile->show_stipends || $profile->show_ratings)
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-val green">
                    @if($profile->show_iprs)
                        {{ $achievements['iprs_score'] }}
                    @else
                        —
                    @endif
                </div>
                <div class="metric-label">IPRS Score</div>
            </div>
            <div class="metric-card">
                <div class="metric-val">{{ $achievements['completed_tasks_count'] }}</div>
                <div class="metric-label">Verified Tasks</div>
            </div>
            <div class="metric-card">
                <div class="metric-val">
                    @if($profile->show_ratings && $achievements['average_rating'])
                        {{ $achievements['average_rating'] }}
                    @else
                        —
                    @endif
                </div>
                <div class="metric-label">Avg Rating</div>
            </div>
            <div class="metric-card">
                <div class="metric-val">
                    @if($profile->show_stipends && $achievements['total_earnings'] > 0)
                        ₹{{ number_format($achievements['total_earnings']) }}
                    @else
                        —
                    @endif
                </div>
                <div class="metric-label">Total Stipend</div>
            </div>
        </div>
    @endif

    <!-- Summary -->
    <div class="section">
        <div class="section-title">Professional Summary</div>
        <p class="summary-text">{{ $summary }}</p>
    </div>

    <!-- Half Grid for Education and Skills -->
    <div class="grid-half">
        <div>
            <div class="section-title" style="margin-bottom: 8px;">Education</div>
            <div class="edu-card">
                <div class="edu-title">{{ $profile->college_name ?: 'University/College Name' }}</div>
                <div class="edu-sub">{{ $profile->degree_name ?: 'Degree Details' }}</div>
                <div class="edu-meta">
                    Graduation: <strong>{{ $profile->graduation_year ?: '—' }}</strong>
                    @if($profile->cgpa)
                        | CGPA: <strong>{{ $profile->cgpa }}</strong>
                    @endif
                </div>
            </div>
        </div>
        
        <div>
            <div class="section-title" style="margin-bottom: 8px;">Technical Stack</div>
            <div class="edu-card" style="display:flex; flex-direction:column; gap:8px;">
                @foreach($skillsCategorized as $cat => $list)
                    <div>
                        <div style="font-size:7.5pt; font-weight:800; color:#64748B; text-transform:uppercase; margin-bottom:3px;">{{ $cat }}</div>
                        <div class="skills-list">
                            @foreach($list as $sk)
                                <span class="skill-pill">{{ $sk }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Experience Timeline -->
    @if(count($experiences) > 0)
        <div class="section">
            <div class="section-title">Verified Experience timeline</div>
            @foreach($experiences as $exp)
                <div class="experience-item">
                    <div class="exp-header">
                        <span class="company-name">{{ $exp['company_name'] }}</span>
                        <span class="duration">{{ $exp['duration'] }}</span>
                    </div>
                    <div class="exp-role">{{ $exp['role'] }}</div>
                    
                    @foreach($exp['projects'] as $proj)
                        <div class="project-row">
                            <span class="project-title">{{ ucfirst($proj['title']) }}</span>
                            <p class="project-desc">{{ $proj['description'] }}</p>
                            <div class="project-tags">
                                <span class="tag">✓ Verified Experience Receipt</span>
                                @if($profile->show_ratings && $proj['rating'])
                                    <span class="tag rating">★ Founder Rating: {{ $proj['rating'] }}/5</span>
                                @endif
                                @if($profile->show_stipends && $proj['stipend'])
                                    <span class="tag">Earnings: ₹{{ number_format($proj['stipend']) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
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
