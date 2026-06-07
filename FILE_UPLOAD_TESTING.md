# File Upload Testing Guide

## How File Upload Should Work

### For Students (Submitting Work):

1. **Go to submission form**
   - Navigate to an approved application
   - Click "Submit Work"

2. **Select files**
   - Click "Choose Files" button
   - Select one or more files (PDF, DOC, images, ZIP)
   - **IMPORTANT**: After selecting, you should immediately see the files listed below the file input with their names and sizes

3. **If you DON'T see files listed**:
   - The JavaScript might not be working
   - Try refreshing the page (Ctrl+F5)
   - Check browser console for errors (F12)

4. **Submit the form**
   - Fill in the content/description
   - Click "Submit Work"
   - You should see "Work submitted successfully!"

### For Startups (Reviewing Submissions):

1. **Go to submission review**
   - From your dashboard, click on a task
   - Click "Review" on a submission

2. **You should see**:
   - Submission content
   - "Uploaded Files" section with all files
   - Each file should have a "Download" button

3. **If you DON'T see files**:
   - Check if the submission actually has files (see debugging below)
   - The submission might have been created before the fix

## Debugging

### Check if files were actually uploaded:

Run this command in terminal:
```bash
php InternGrowth\artisan tinker --execute="echo json_encode(\App\Models\Submission::find(YOUR_SUBMISSION_ID)->files);"
```

Replace `YOUR_SUBMISSION_ID` with the actual submission ID.

### Expected output:
- **With files**: `[{"name":"file.pdf","path":"submissions/xxx.pdf","size":1234,"type":"application/pdf"}]`
- **Without files**: `null` or `[[]]`

### Check if files exist on disk:

```bash
dir InternGrowth\storage\app\public\submissions
```

You should see PDF files with random names.

### Check if storage link works:

```bash
Test-Path InternGrowth\public\storage\submissions
```

Should return `True`.

## Common Issues

### Issue 1: Files not showing in form after selection
**Cause**: JavaScript not loading or browser cache
**Fix**: 
- Hard refresh (Ctrl+F5)
- Clear browser cache
- Check browser console for errors

### Issue 2: Files not uploading
**Cause**: Form missing `enctype="multipart/form-data"`
**Status**: ✅ Fixed - form has correct enctype

### Issue 3: Files showing as `[[]]` in database
**Cause**: Old bug where empty arrays were stored
**Status**: ✅ Fixed - now stores `null` when no files
**Note**: Old submissions (ID 1-8) still have this issue

### Issue 4: Files uploaded but not showing in review
**Cause**: Display logic checking for wrong conditions
**Status**: ✅ Fixed - now properly checks for valid file arrays

## Test Submissions

Based on database check:
- **Submissions 1-8**: Have `[[]]` (no files, old bug)
- **Submissions 9-10**: Have actual files ✅

To test properly:
1. Create a NEW submission with files
2. The files should show in the form when you select them
3. After submitting, startup should see files in review page
4. Startup should be able to download each file

## File Upload Limits

- Max file size: 10MB per file
- Allowed types: PDF, DOC, DOCX, TXT, ZIP, JPG, JPEG, PNG, GIF
- Multiple files: Yes, unlimited number

## Debug Mode

If `APP_DEBUG=true` in `.env`, the review page will show raw file data in a yellow box. This helps verify what's stored in the database.
