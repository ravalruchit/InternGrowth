<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Verification Code</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">InternGrowth</h1>
        <p style="color: #e0e7ff; margin: 10px 0 0 0;">Email Verification Code</p>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1f2937; margin-top: 0;">Hello {{ $profile->user->name }}!</h2>
        
        <p style="color: #4b5563;">Thank you for verifying your college email with InternGrowth. Use the code below to complete your verification:</p>
        
        <div style="background: white; padding: 30px; border-radius: 8px; margin: 30px 0; text-align: center; border: 2px dashed #667eea;">
            <p style="margin: 0; color: #6b7280; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Your Verification Code</p>
            <p style="margin: 15px 0 0 0; font-size: 48px; font-weight: bold; color: #667eea; letter-spacing: 8px; font-family: 'Courier New', monospace;">
                {{ $code }}
            </p>
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
            <p style="margin: 0; color: #6b7280; font-size: 14px;">College: <strong style="color: #1f2937;">{{ $profile->college_name }}</strong></p>
            <p style="margin: 10px 0 0 0; color: #6b7280; font-size: 14px;">Email: <strong style="color: #1f2937;">{{ $profile->college_email }}</strong></p>
        </div>
        
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <p style="margin: 0; color: #92400e; font-size: 14px;">
                <strong>⚠️ Important:</strong> This code will expire in 24 hours. If you didn't request this verification, please ignore this email.
            </p>
        </div>
        
        <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
            Enter this code on the InternGrowth verification page to unlock access to all tasks and opportunities.
        </p>
        
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">
        
        <p style="color: #9ca3af; font-size: 12px; text-align: center; margin: 0;">
            © {{ date('Y') }} InternGrowth. All rights reserved.<br>
            This is an automated email, please do not reply.
        </p>
    </div>
</body>
</html>
