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
            padding: 5px;
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
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4F46E5;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-family: inherit;
            font-size: 9pt;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
            transition: background 0.2s;
            z-index: 1000;
        }
        .print-btn:hover {
            background: #4338CA;
        }
        @media print {
            .print-btn {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">📥 Save / Print PDF</button>

    <!-- Header -->
    <div class="header">
        <h1>{{ $profile->user->name }}</h1>
        @if($profile->professional_title)
            <div class="title">{{ $profile->professional_title }}</div>
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
                            <strong>{{ $proj['title'] }}</strong> — {{ $proj['description'] }}
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

    <!-- Certifications -->
    @if($profile->show_certificates && $profile->certificates->count() > 0)
        <div class="section">
            <div class="section-title">Certifications</div>
            <div class="skills-container" style="gap:8px;">
                @foreach($profile->certificates as $cert)
                    <div class="skill-pill" style="padding: 6px 12px; background: #EEF2F6; border: none; font-size: 8.5pt; font-weight:600;">
                        🎓 InternGrowth Verified Developer Certificate #{{ $cert->certificate_number }} ({{ $cert->issued_at->format('M Y') }})
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</body>
</html>
