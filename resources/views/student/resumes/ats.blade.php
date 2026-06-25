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
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #111111;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            font-size: 9pt;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 1000;
        }
        @media print {
            .print-btn {
                display: none;
            }
            body {
                font-size: 10.5pt;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Print / Save PDF</button>

    <!-- Header -->
    <div class="header">
        <h1>{{ $profile->user->name }}</h1>
        @if($profile->professional_title)
            <div class="title">{{ $profile->professional_title }}</div>
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
                                <strong>{{ $proj['title'] }}</strong>: {{ $proj['description'] }}
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

    <!-- Certifications -->
    @if($profile->show_certificates && $profile->certificates->count() > 0)
        <div class="section">
            <div class="section-title">Certifications</div>
            <ul class="bullet-list">
                @foreach($profile->certificates as $cert)
                    <li>InternGrowth Verified Completion Certificate #{{ $cert->certificate_number }} (Issued: {{ $cert->issued_at->format('F Y') }})</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>
