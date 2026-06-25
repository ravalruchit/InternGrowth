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
            color: #0B0F14;
            line-height: 1.4;
            font-size: 9pt;
            background: #ffffff;
        }
        body.direct-view {
            background: #F4F1EA;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .resume-container {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 297mm;
            background: #ffffff;
            width: 100%;
        }
        body.direct-view .resume-container {
            width: 210mm;
            box-shadow: 0 15px 35px rgba(11, 15, 20, 0.1);
            border: 1px solid #D6CFBE;
            border-radius: 12px;
            overflow: hidden;
        }
        @media print {
            body.direct-view {
                background: #ffffff;
                padding: 0;
            }
            body.direct-view .resume-container {
                width: 100%;
                box-shadow: none;
                border: none;
                border-radius: 0;
            }
        }
        
        /* Left Column (Sidebar) - Matches warm cream secondary background */
        .sidebar {
            background: #ECE7DC;
            color: #0B0F14;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            border-right: 1px solid #D6CFBE;
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
            background: #FF4F19; /* Tangerine */
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32pt;
            font-weight: 800;
            border: 3px solid #D6CFBE;
        }
        
        .sidebar-section-title {
            font-size: 8pt;
            font-weight: 800;
            color: #FF4F19; /* Tangerine */
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #D6CFBE;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }
        .sidebar-item {
            margin-bottom: 10px;
        }
        .sidebar-item-title {
            font-weight: 700;
            font-size: 8.5pt;
            color: #0B0F14;
        }
        .sidebar-item-desc {
            font-size: 8pt;
            color: #5C6470;
            margin-top: 1px;
        }
        
        /* Skills on sidebar */
        .skills-group {
            margin-bottom: 8px;
        }
        .skills-group-title {
            font-size: 7.5pt;
            font-weight: 700;
            color: #5C6470;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        .skill-badge {
            background: #FFFFFF;
            color: #0B0F14;
            font-size: 7.5pt;
            padding: 1px 6px;
            border: 1px solid #D6CFBE;
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
            color: #0B0F14;
            line-height: 1.1;
        }
        .main-header .subtitle {
            font-size: 11pt;
            font-weight: 600;
            color: #FF4F19; /* Tangerine */
            margin-top: 2px;
            margin-bottom: 12px;
        }
        .contact-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 8pt;
            color: #5C6470;
        }
        .contact-row a {
            color: #FF4F19;
            text-decoration: none;
            font-weight: 600;
        }
        .summary-text {
            color: #2B3038;
            font-size: 8.5pt;
            text-align: justify;
        }
        .section-title {
            font-size: 10pt;
            font-weight: 800;
            color: #0B0F14;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #D6CFBE;
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
            color: #0B0F14;
            font-size: 9.5pt;
        }
        .duration {
            font-size: 8pt;
            color: #5C6470;
            font-weight: 600;
        }
        .exp-role {
            font-weight: 600;
            color: #FF4F19;
            font-size: 8.5pt;
            margin-bottom: 6px;
        }
        .project-bullet {
            margin-bottom: 4px;
            padding-left: 12px;
            position: relative;
            font-size: 8.5pt;
            color: #2B3038;
        }
        .project-bullet::before {
            content: ">";
            position: absolute;
            left: 0;
            color: #FF4F19;
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
            background: #ECE7DC;
            color: #2B3038;
            padding: 0 4px;
            border-radius: 3px;
            font-weight: 600;
        }
        .tag.verified {
            background: #FFE3D6;
            color: #FF4F19;
        }

        /* Portfolio project cards */
        .projects-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .project-card {
            background: #F4F1EA; /* warm cream */
            border: 1px solid #E5E0D3;
            border-radius: 6px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-title {
            font-weight: 700;
            font-size: 8.5pt;
            color: #0B0F14;
        }
        .card-desc {
            font-size: 8pt;
            color: #5C6470;
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
            color: #FF4F19;
            text-decoration: none;
            font-weight: 600;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #FF4F19;
            color: #ffffff;
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
                    <div class="sidebar-item-desc" style="color: #FF4F19; font-weight: 500;">
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
                                    <strong>{{ ucfirst($proj['title']) }}</strong> — {{ $proj['description'] }}
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
