<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion - {{ $certificate->student->user->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .certificate-container {
            background: white;
            width: 100%;
            max-width: 1000px;
            padding: 60px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        /* Decorative Border */
        .certificate-border {
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 3px solid #667eea;
            pointer-events: none;
        }
        
        .certificate-border::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 1px solid #d4af37;
        }
        
        /* Corner Decorations */
        .corner-decoration {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 2px solid #d4af37;
        }
        
        .corner-decoration.top-left {
            top: 20px;
            left: 20px;
            border-right: none;
            border-bottom: none;
        }
        
        .corner-decoration.top-right {
            top: 20px;
            right: 20px;
            border-left: none;
            border-bottom: none;
        }
        
        .corner-decoration.bottom-left {
            bottom: 20px;
            left: 20px;
            border-right: none;
            border-top: none;
        }
        
        .corner-decoration.bottom-right {
            bottom: 20px;
            right: 20px;
            border-left: none;
            border-top: none;
        }
        
        .certificate-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        
        .certificate-title {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }
        
        .certificate-subtitle {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 20px;
            font-weight: 300;
            letter-spacing: 1px;
        }
        
        .student-name {
            font-family: 'Playfair Display', serif;
            font-size: 56px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 30px 0;
            padding: 20px 0;
            border-top: 2px solid #e2e8f0;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .completion-text {
            font-size: 18px;
            color: #4a5568;
            margin: 25px 0;
            line-height: 1.8;
        }
        
        .task-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 600;
            color: #2d3748;
            margin: 25px 0;
            font-style: italic;
        }
        
        .certificate-details {
            display: flex;
            justify-content: space-around;
            margin: 50px 0 30px 0;
            padding: 30px 0;
            border-top: 1px solid #e2e8f0;
        }
        
        .detail-item {
            text-align: center;
        }
        
        .detail-label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .detail-value {
            font-size: 16px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-around;
            margin-top: 60px;
            padding-top: 20px;
        }
        
        .signature {
            text-align: center;
            min-width: 200px;
        }
        
        .signature-line {
            border-top: 2px solid #2d3748;
            margin-bottom: 10px;
            padding-top: 40px;
        }
        
        .signature-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 16px;
        }
        
        .signature-title {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
        }
        
        .qr-section {
            position: absolute;
            bottom: 50px;
            right: 50px;
            text-align: center;
        }
        
        .qr-code {
            width: 100px;
            height: 100px;
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            font-size: 10px;
            color: #a0aec0;
        }
        
        .verify-text {
            font-size: 10px;
            color: #718096;
        }
        
        .seal {
            position: absolute;
            bottom: 50px;
            left: 50px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            text-align: center;
            line-height: 1.3;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .download-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            color: #667eea;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: 2px solid #667eea;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .download-btn:hover {
            background: #667eea;
            color: white;
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
            
            .certificate-container {
                box-shadow: none;
                max-width: 100%;
                page-break-inside: avoid;
            }
        }
        
        @media (max-width: 768px) {
            .certificate-container {
                padding: 30px 20px;
            }
            
            .certificate-title {
                font-size: 32px;
            }
            
            .student-name {
                font-size: 36px;
            }
            
            .task-title {
                font-size: 24px;
            }
            
            .seal, .qr-section {
                position: static;
                margin: 20px auto;
            }
            
            .signature-section {
                flex-direction: column;
                gap: 30px;
            }
        }
    </style>
</head>
<body>
    <button class="download-btn" onclick="window.print()">📥 Download PDF</button>
    
    <div class="certificate-container">
        <div class="certificate-border"></div>
        <div class="corner-decoration top-left"></div>
        <div class="corner-decoration top-right"></div>
        <div class="corner-decoration bottom-left"></div>
        <div class="corner-decoration bottom-right"></div>
        
        <div class="certificate-content">
            <div class="logo">InternGrowth</div>
            
            @if($certificate->hiringOffer)
                <h1 class="certificate-title">Certificate of Experience</h1>
            @else
                <h1 class="certificate-title">Certificate of Completion</h1>
            @endif
            
            <p class="certificate-subtitle">THIS IS TO CERTIFY THAT</p>
            
            <h2 class="student-name">{{ $certificate->student->user->name }}</h2>
            
            @if($certificate->hiringOffer)
                <p class="completion-text">
                    has successfully completed a placement as a <strong>{{ $certificate->hiringOffer->role }}</strong><br>
                    and demonstrated exceptional skills, dedication, and professionalism in
                </p>
                <h3 class="task-title">"{{ $certificate->hiringOffer->title }}"</h3>
                <p class="completion-text">
                    from {{ $certificate->hiringOffer->start_date->format('F d, Y') }} to {{ $certificate->hiringOffer->completed_at ? $certificate->hiringOffer->completed_at->format('F d, Y') : ($certificate->hiringOffer->end_date ? $certificate->hiringOffer->end_date->format('F d, Y') : 'N/A') }}.
                </p>
            @else
                <p class="completion-text">
                    has successfully completed the internship task and demonstrated<br>
                    exceptional skills, dedication, and professionalism in
                </p>
                <h3 class="task-title">"{{ $certificate->task->title }}"</h3>
                <p class="completion-text">
                    This achievement reflects a commitment to excellence and<br>
                    a strong foundation for future professional endeavors.
                </p>
            @endif
            
            <div class="certificate-details">
                <div class="detail-item">
                    <div class="detail-label">Certificate Number</div>
                    <div class="detail-value">{{ $certificate->certificate_number }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date of Issue</div>
                    <div class="detail-value">{{ $certificate->issued_at->format('F d, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Domain</div>
                    <div class="detail-value">
                        {{ $certificate->hiringOffer ? ($certificate->hiringOffer->domain ?? 'Software Development') : $certificate->task->domain }}
                    </div>
                </div>
            </div>
            
            <div class="signature-section">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">
                        {{ $certificate->hiringOffer ? $certificate->hiringOffer->startup->company_name : $certificate->task->startup->company_name }}
                    </div>
                    <div class="signature-title">Startup Representative</div>
                </div>
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">InternGrowth Platform</div>
                    <div class="signature-title">Platform Administrator</div>
                </div>
            </div>
        </div>
        
        <div class="seal">
            VERIFIED<br>ACHIEVEMENT
        </div>
        
        <div class="qr-section">
            <div class="qr-code">QR CODE</div>
            <div class="verify-text">Scan to verify</div>
        </div>
    </div>
</body>
</html>
