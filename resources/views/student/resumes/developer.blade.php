<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->user->name }} - Developer Resume</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@400;500;600;700;800;900&display=swap">
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            color: #1E293B;
            line-height: 1.4;
            font-size: 9pt;
            background: #ffffff;
        }
        .resume-container {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 297mm;
        }
        
        /* Left Column (Sidebar) */
        .sidebar {
            background: #0F172A;
            color: #F1F5F9;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .profile-photo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }
        .profile-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #4F46E5;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32pt;
            font-weight: 800;
            border: 3px solid #334155;
        }
        
        .sidebar-section-title {
            font-size: 8pt;
            font-weight: 800;
            color: #38BDF8; /* Sky accent */
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #334155;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }
        .sidebar-item {
            margin-bottom: 10px;
        }
        .sidebar-item-title {
            font-weight: 700;
            font-size: 8.5pt;
            color: #F1F5F9;
        }
        .sidebar-item-desc {
            font-size: 8pt;
            color: #94A3B8;
            margin-top: 1px;
        }
        
        /* Skills on sidebar */
        .skills-group {
            margin-bottom: 8px;
        }
        .skills-group-title {
            font-size: 7.5pt;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        .skill-badge {
            background: #1E293B;
            color: #38BDF8;
            font-size: 7.5pt;
            padding: 1px 6px;
            border: 1px solid #334155;
            border-radius: 4px;
            font-weight: 500;
            font-family: 'Fira Code', monospace;
        }
        
        /* Right Column (Content) */
        .main-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .main-header h1 {
            font-size: 24pt;
            font-weight: 900;
            color: #0F172A;
            line-height: 1.1;
        }
        .main-header .subtitle {
            font-size: 11pt;
            font-weight: 600;
            color: #4F46E5;
            margin-top: 2px;
            margin-bottom: 12px;
        }
        .contact-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 8pt;
            color: #475569;
        }
        .contact-row a {
            color: #4F46E5;
            text-decoration: none;
            font-weight: 600;
        }
        .summary-text {
            color: #334155;
            font-size: 8.5pt;
            text-align: justify;
        }
        .section-title {
            font-size: 10pt;
            font-weight: 800;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #E2E8F0;
            padding-bottom: 3px;
            margin-bottom: 12px;
        }
        .experience-card {
            margin-bottom: 14px;
        }
        .exp-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        .company-name {
            font-weight: 800;
            color: #0F172A;
            font-size: 9.5pt;
        }
        .duration {
            font-size: 8pt;
            color: #64748B;
            font-weight: 600;
        }
        .exp-role {
            font-weight: 600;
            color: #4F46E5;
            font-size: 8.5pt;
            margin-bottom: 6px;
        }
        .project-bullet {
            margin-bottom: 4px;
            padding-left: 12px;
            position: relative;
            font-size: 8.5pt;
            color: #334155;
        }
        .project-bullet::before {
            content: ">";
            position: absolute;
            left: 0;
            color: #38BDF8;
            font-family: 'Fira Code', monospace;
            font-weight: bold;
        }
        .project-tags {
            display: inline-flex;
            gap: 6px;
            font-size: 7.5pt;
            margin-left: 6px;
        }
        .tag {
            background: #F1F5F9;
            color: #475569;
            padding: 0 4px;
            border-radius: 3px;
            font-weight: 600;
        }
        .tag.verified {
            background: #E0F2FE;
            color: #0369A1;
        }

        /* Portfolio project cards */
        .projects-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .project-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-title {
            font-weight: 700;
            font-size: 8.5pt;
            color: #0F172A;
        }
        .card-desc {
            font-size: 8pt;
            color: #475569;
            margin-top: 4px;
            margin-bottom: 8px;
            text-align: justify;
        }
        .card-links {
            display: flex;
            gap: 8px;
            font-size: 7.5pt;
        }
        .card-links a {
            color: #4F46E5;
            text-decoration: none;
            font-weight: 600;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #38BDF8;
            color: #0F172A;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-family: inherit;
            font-size: 8.5pt;
            font-weight: 700;
            cursor: pointer;
            z-index: 1000;
        }
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Print Resume</button>

    <div class="resume-container">
        <!-- Sidebar -->
        <div class="sidebar">
            @if($profile->show_profile_photo)
                <div class="profile-photo-container">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                    </div>
                </div>
            @endif

            <!-- Education -->
            <div>
                <div class="sidebar-section-title">Education</div>
                <div class="sidebar-item">
                    <div class="sidebar-item-title">{{ $profile->college_name ?: 'University Name' }}</div>
                    <div class="sidebar-item-desc">{{ $profile->degree_name ?: 'Degree Details' }}</div>
                    <div class="sidebar-item-desc" style="color: #38BDF8; font-weight: 500;">
                        {{ $profile->graduation_year ? 'Class of ' . $profile->graduation_year : '' }}
                        @if($profile->cgpa)
                            | CGPA: {{ $profile->cgpa }}
                        @endif
                    </div>
                </div>
            </div>

            <!-- Technical Stack -->
            @if(count($skillsCategorized) > 0)
                <div>
                    <div class="sidebar-section-title">Technical Stack</div>
                    @foreach($skillsCategorized as $cat => $list)
                        <div class="skills-group">
                            <div class="skills-group-title">{{ $cat }}</div>
                            <div class="skills-list">
                                @foreach($list as $sk)
                                    <span class="skill-badge">{{ $sk }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Achievements -->
            @if($profile->show_iprs || $profile->show_stipends || $profile->show_ratings)
                <div>
                    <div class="sidebar-section-title">Metrics & Rank</div>
                    @if($profile->show_iprs && $achievements['iprs_score'])
                        <div class="sidebar-item">
                            <div class="sidebar-item-title" style="color: #10B981;">IPRS Score: {{ $achievements['iprs_score'] }}</div>
                            <div class="sidebar-item-desc">{{ $achievements['iprs_rank'] }}</div>
                        </div>
                    @endif
                    <div class="sidebar-item">
                        <div class="sidebar-item-title">{{ $achievements['completed_tasks_count'] }} Verified Tasks</div>
                        <div class="sidebar-item-desc">Completed on marketplace</div>
                    </div>
                    @if($profile->show_stipends && $achievements['total_earnings'] > 0)
                        <div class="sidebar-item">
                            <div class="sidebar-item-title">₹{{ number_format($achievements['total_earnings']) }} Earned</div>
                            <div class="sidebar-item-desc">Stipend payouts</div>
                        </div>
                    @endif
                    @if($profile->show_ratings && $achievements['average_rating'])
                        <div class="sidebar-item">
                            <div class="sidebar-item-title">★ {{ $achievements['average_rating'] }}/5 Rating</div>
                            <div class="sidebar-item-desc">Founder review average</div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Certifications -->
            @if($profile->show_certificates && $profile->certificates->count() > 0)
                <div>
                    <div class="sidebar-section-title">Certifications</div>
                    @foreach($profile->certificates->take(2) as $cert)
                        <div class="sidebar-item" style="margin-bottom:8px;">
                            <div class="sidebar-item-title" style="font-size:8pt;">Verified Developer</div>
                            <div class="sidebar-item-desc" style="font-size:7.5pt;">Cert ID: {{ substr($cert->certificate_number, 0, 10) }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Main Header -->
            <div class="main-header">
                <h1>{{ $profile->user->name }}</h1>
                @if($profile->professional_title)
                    <div class="subtitle">{{ $profile->professional_title }}</div>
                @endif
                
                <div class="contact-row">
                    @if($profile->city || $profile->state)
                        <span>📍 {{ implode(', ', array_filter([$profile->city, $profile->state, $profile->country])) }}</span>
                    @endif
                    @if($profile->phone_number)
                        <span>📞 {{ $profile->phone_number }}</span>
                    @endif
                    <span>✉️ {{ $profile->user->email }}</span>
                </div>
                
                @if($profile->show_social_links)
                    <div class="contact-row" style="margin-top: 5px; font-weight:600;">
                        @if($profile->github_url)
                            <span>GitHub: <a href="{{ $profile->github_url }}" target="_blank">{{ basename($profile->github_url) }}</a></span>
                        @endif
                        @if($profile->linkedin_url)
                            <span>LinkedIn: <a href="{{ $profile->linkedin_url }}" target="_blank">{{ basename($profile->linkedin_url) }}</a></span>
                        @endif
                        @if($profile->portfolio_url)
                            <span>Portfolio: <a href="{{ $profile->portfolio_url }}" target="_blank">demo</a></span>
                        @endif
                        @if($profile->leetcode_url)
                            <span>LeetCode: <a href="{{ $profile->leetcode_url }}" target="_blank">{{ basename($profile->leetcode_url) }}</a></span>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Summary -->
            <div>
                <p class="summary-text">{{ $summary }}</p>
            </div>

            <!-- Verified Experience -->
            @if(count($experiences) > 0)
                <div>
                    <div class="section-title">Verified Experience</div>
                    @foreach($experiences as $exp)
                        <div class="experience-card">
                            <div class="exp-header">
                                <span class="company-name">{{ $exp['company_name'] }}</span>
                                <span class="duration">{{ $exp['duration'] }}</span>
                            </div>
                            <div class="exp-role">{{ $exp['role'] }}</div>
                            @foreach($exp['projects'] as $proj)
                                <div class="project-bullet">
                                    <strong>{{ $proj['title'] }}</strong> — {{ $proj['description'] }}
                                    @if($profile->show_ratings && $proj['rating'])
                                        <span class="project-tags">
                                            <span class="tag verified">✓ Verified</span>
                                            <span class="tag">Rating: {{ $proj['rating'] }}/5</span>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Projects (from manual portfolio items) -->
            @if($profile->portfolio && $profile->portfolio->items->isNotEmpty())
                <div>
                    <div class="section-title">Portfolio Projects</div>
                    <div class="projects-grid">
                        @foreach($profile->portfolio->items->take(4) as $item)
                            <div class="project-card">
                                <div>
                                    <div class="card-title">{{ $item->project_title }}</div>
                                    <div class="card-desc">{{ $item->auto_summary ?: 'Demonstrated key developer competencies.' }}</div>
                                </div>
                                <div class="card-links">
                                    @if($item->github_url)
                                        <a href="{{ $item->github_url }}" target="_blank">Code</a>
                                    @endif
                                    @if($item->demo_url)
                                        <a href="{{ $item->demo_url }}" target="_blank">Demo</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
