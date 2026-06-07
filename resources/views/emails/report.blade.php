<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Report - InternGrowth</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">⚠️ New Report Submitted</h1>
        <p style="color: #fee2e2; margin: 10px 0 0 0;">InternGrowth Platform</p>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px;">
        <h2 style="color: #1f2937; margin-top: 0;">Report Details</h2>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc2626;">
            <p style="margin: 0; color: #6b7280; font-size: 14px;"><strong>Report Type:</strong></p>
            <p style="margin: 5px 0 15px 0; font-size: 16px; color: #1f2937;">{{ ucfirst($reportType) }}</p>
            
            <p style="margin: 0; color: #6b7280; font-size: 14px;"><strong>Subject:</strong></p>
            <p style="margin: 5px 0 15px 0; font-size: 16px; color: #1f2937;">{{ $subject }}</p>
            
            <p style="margin: 0; color: #6b7280; font-size: 14px;"><strong>Description:</strong></p>
            <p style="margin: 5px 0 15px 0; font-size: 14px; color: #1f2937; white-space: pre-wrap;">{{ $description }}</p>
            
            @if($reportedId)
                <p style="margin: 0; color: #6b7280; font-size: 14px;"><strong>Reported ID:</strong></p>
                <p style="margin: 5px 0 0 0; font-size: 14px; color: #1f2937;">{{ $reportedId }}</p>
            @endif
        </div>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="color: #1f2937; margin-top: 0;">Reporter Information</h3>
            <p style="margin: 5px 0; color: #4b5563; font-size: 14px;"><strong>Name:</strong> {{ $reporter->name }}</p>
            <p style="margin: 5px 0; color: #4b5563; font-size: 14px;"><strong>Email:</strong> {{ $reporter->email }}</p>
            <p style="margin: 5px 0; color: #4b5563; font-size: 14px;"><strong>Role:</strong> {{ ucfirst($reporter->role) }}</p>
            <p style="margin: 5px 0; color: #4b5563; font-size: 14px;"><strong>User ID:</strong> {{ $reporter->id }}</p>
        </div>
        
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 5px;">
            <p style="margin: 0; color: #92400e; font-size: 14px;">
                <strong>⏰ Reported At:</strong> {{ $reportedAt->format('F d, Y H:i:s') }}
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="http://localhost:8000/admin/dashboard" style="background: #dc2626; color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; display: inline-block; font-weight: bold;">
                View in Admin Panel
            </a>
        </div>
        
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">
        
        <p style="color: #9ca3af; font-size: 12px; text-align: center; margin: 0;">
            © {{ date('Y') }} InternGrowth. All rights reserved.<br>
            This is an automated email, please do not reply.
        </p>
    </div>
</body>
</html>
