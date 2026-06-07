<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - {{ $profile->user->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }
        
        .cv-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .cv-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
        }
        
        .cv-header h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .cv-header .subtitle {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .contact-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 14px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .cv-body {
            padding: 40px;
        }
        
        .section {
            margin-bottom: 35px;
        }
        
        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }
        
        .summary {
            color: #4b5563;
            line-height: 1.7;
            font-size: 15px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #667eea;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .skill-tag {
            background: #e0e7ff;
            color: #667eea;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .experience-item {
            margin-bottom: 25px;
            padding-left: 20px;
            border-left: 2px solid #e5e7eb;
        }
        
        .experience-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 8px;
        }
        
        .experience-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }
        
        .experience-company {
            font-size: 15px;
            color: #667eea;
            margin-bottom: 4px;
        }
        
        .experience-date {
            font-size: 13px;
            color: #6b7280;
        }
        
        .experience-details {
            display: flex;
            gap: 15px;
            margin-top: 8px;
            font-size: 13px;
        }
        
        .detail-badge {
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 4px;
            color: #4b5563;
        }
        
        .detail-badge.points {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .detail-badge.stipend {
            background: #d1fae5;
            color: #065f46;
        }
        
        .detail-badge.rating {
            background: #fef3c7;
            color: #92400e;
        }
        
        .certificates-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .certificate-card {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .certificate-title {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 6px;
        }
        
        .certificate-meta {
            font-size: 12px;
            color: #6b7280;
        }
        
        .download-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s;
        }
        
        .download-btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .download-btn {
                display: none;
            }
            
            .cv-container {
                box-shadow: none;
                max-width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .certificates-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <button class="download-btn" onclick="window.print()">📥 Download PDF</button>
    
    <div class="cv-container">
        <!-- Header -->
        <div class="cv-header">
            <h1>{{ $profile->user->name }}</h1>
            <div class="subtitle">InternGrowth Platform - Professional Profile</div>
            <div class="contact-info">
                <div class="contact-item">
                    <span>📧</span>
                    <span>{{ $profile->user->email }}</span>
                </div>
                @if($profile->college_email && $profile->is_verified)
                    <div class="contact-item">
                        <span>🎓</span>
                        <span>{{ $profile->college_email }}</span>
                    </div>
                @endif
                @if($profile->college_name)
                    <div class="contact-item">
                        <span>🏫</span>
                        <span>{{ $profile->college_name }}</span>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Body -->
        <div class="cv-body">
            <!-- Professional Summary -->
            @if($profile->bio)
                <div class="section">
                    <h2 class="section-title">Professional Summary</h2>
                    <p class="summary">{{ $profile->bio }}</p>
                </div>
            @endif
            
            <!-- Key Metrics -->
            <div class="section">
                <h2 class="section-title">Performance Metrics</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">{{ $completedTasks->count() }}</div>
                        <div class="stat-label">Tasks Completed</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($totalPoints) }}</div>
                        <div class="stat-label">Reward Points</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">₹{{ number_format($totalStipend, 0) }}</div>
                        <div class="stat-label">Total Stipend</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($profile->ratings->avg('rating') ?? 0, 1) }}</div>
                        <div class="stat-label">Avg Rating</div>
                    </div>
                </div>
            </div>
            
            <!-- Skills -->
            @if($profile->skills->count() > 0)
                <div class="section">
                    <h2 class="section-title">Skills & Expertise</h2>
                    <div class="skills-list">
                        @foreach($profile->skills as $skill)
                            <span class="skill-tag">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Work Experience -->
            @if($completedTasks->count() > 0)
                <div class="section">
                    <h2 class="section-title">Internship Experience</h2>
                    @foreach($completedTasks->sortByDesc('submission.updated_at') as $task)
                        <div class="experience-item">
                            <div class="experience-header">
                                <div>
                                    <div class="experience-title">{{ $task->task->title }}</div>
                                    <div class="experience-company">{{ $task->task->startup->company_name }}</div>
                                    <div class="experience-date">
                                        Completed: {{ $task->submission->updated_at->format('F Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="experience-details">
                                <span class="detail-badge points">{{ $task->task->reward_points }} Points</span>
                                @if($task->task->stipend)
                                    <span class="detail-badge stipend">₹{{ $task->task->stipend }} Stipend</span>
                                @endif
                                @if($task->rating)
                                    <span class="detail-badge rating">⭐ {{ $task->rating->rating }}/5</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Certificates -->
            @if($profile->certificates->count() > 0)
                <div class="section">
                    <h2 class="section-title">Certificates & Achievements</h2>
                    <div class="certificates-grid">
                        @foreach($profile->certificates as $certificate)
                            <div class="certificate-card">
                                <div class="certificate-title">{{ $certificate->task->title }}</div>
                                <div class="certificate-meta">
                                    Issued by {{ $certificate->task->startup->company_name }}<br>
                                    {{ $certificate->issued_at->format('F d, Y') }}<br>
                                    Certificate #{{ $certificate->certificate_number }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Footer -->
            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 12px;">
                <p>This CV was generated from InternGrowth Platform on {{ now()->format('F d, Y') }}</p>
                <p style="margin-top: 5px;">Verify authenticity at: interngrowth.com/verify</p>
            </div>
        </div>
    </div>
</body>
</html>
