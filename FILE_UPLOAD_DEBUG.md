# File Upload Debugging Steps

## Issue
Files are selected in the form (showing in preview) but not appearing after submission.

## Steps to Debug

### 1. Check Browser Console
1. Open the submission/revision form
2. Press F12 to open Developer Tools
3. Go to "Console" tab
4. Select a file
5. Check for any JavaScript errors

### 2. Check Network Request
1. Keep Developer Tools open (F12)
2. Go to "Network" tab
3. Select a file in the form
4. Click "Submit"
5. Look for the POST request to `/applications/{id}/submit` or `/submissions/{id}/revise`
6. Click on that request
7. Go to "Payload" or "Request" tab
8. Check if `files[]` is present with the file data

### 3. Check Laravel Logs
After submitting, run:
```bash
Get-Content "InternGrowth\storage\logs\laravel.log" -Tail 50
```

Look for lines like:
- `Revision update started`
- `has_files: true/false`
- `files_count: X`
- `File uploaded`

### 4. Check Database
```bash
php InternGrowth\artisan tinker --execute="echo json_encode(\App\Models\Submission::latest()->first()->files);"
```

Should show file data, not `null` or `[[]]`.

### 5. Check File System
```bash
dir InternGrowth\storage\app\public\submissions
```

New files should appear with random names after submission.

## Common Causes

### Cause 1: Max Upload Size
**Check**: `php.ini` settings
- `upload_max_filesize` (default: 2M)
- `post_max_size` (default: 8M)

**Fix**: Increase these values or use smaller files for testing.

### Cause 2: Form Not Submitting Files
**Check**: Network tab shows files in payload
**Fix**: Ensure `enctype="multipart/form-data"` is present (already added).

### Cause 3: Validation Failing Silently
**Check**: Laravel logs for validation errors
**Fix**: Check file size (max 10MB) and file type.

### Cause 4: JavaScript Preventing Submission
**Check**: Console for errors
**Fix**: Remove `onchange` handler temporarily to test.

## Quick Test

Try submitting WITHOUT selecting a file first. Then check if the submission works. If it does, the issue is specifically with file handling.

## Current Status

✅ Form has `enctype="multipart/form-data"`
✅ JavaScript preview is working
✅ Controller has file handling code
✅ Storage directory exists
✅ Storage link exists
❓ Files not appearing in database/review page

Next step: Check browser Network tab to see if files are being sent.
