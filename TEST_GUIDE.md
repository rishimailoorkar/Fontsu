# Quick Test Guide

## Prerequisites
1. PHP installed on your system
2. Supabase project created and configured (see SUPABASE_SETUP.md)
3. Updated `php/config.php` with your Supabase credentials

## Step 1: Start PHP Server

Open PowerShell in the project directory and run:

```powershell
cd "c:\Users\mailo\Downloads\Namma Fontsu"
php -S localhost:8000
```

You should see:
```
PHP 8.x Development Server started at http://localhost:8000
```

## Step 2: Test Upload

1. Open browser and go to: http://localhost:8000/designers.html
2. Fill out the form:
   - Font Name: `Test Kannada Font`
   - Script: `Kannada`
   - Designer Name: `Your Name`
   - Category: `Display`
   - Personal Price: `499`
   - Commercial Price: `2499`
   - Description: `A beautiful test font`
   - Upload a TTF/OTF file (or any file for initial testing)
3. Click "Submit for Review"
4. You should see: "Font uploaded successfully and submitted for review!"

## Step 3: Verify in Supabase

1. Go to your Supabase dashboard
2. Navigate to Table Editor > fonts
3. You should see a new row with:
   - name: "Test Kannada Font"
   - status: "pending"
   - All other fields populated

## Step 4: Approve Font

In Supabase Table Editor:
1. Find your font in the fonts table
2. Click on the row to edit
3. Change `status` from `pending` to `approved`
4. Save changes

## Step 5: View on Frontend

1. Go to: http://localhost:8000/fonts.html
2. Wait a few seconds or refresh the page
3. Your uploaded font should appear in the grid
4. It will auto-refresh every 10 seconds

## Troubleshooting Quick Fixes

### Error: "Failed to upload file to storage"
- Check Supabase Storage bucket `fonts` exists
- Verify bucket is set to public
- Check storage policies allow insert

### Error: "Failed to save font data"
- Verify Supabase table `fonts` exists with correct schema
- Check API keys in config.php are correct
- Ensure table policies allow insert

### Font not showing on fonts.html
- Check font status is `approved` in database
- Verify `php/get_fonts.php` is accessible
- Check browser console for errors

### CORS errors
- Ensure you're accessing via http://localhost:8000, not file://
- Check CORS headers in config.php

## Test API Endpoints Directly

### Test Get Fonts
Open in browser:
```
http://localhost:8000/php/get_fonts.php?status=approved
```

Expected response:
```json
{
  "success": true,
  "count": 5,
  "data": [...]
}
```

### Test Upload (using curl)
```powershell
curl -X POST http://localhost:8000/php/upload_font.php `
  -F "font_name=Test Font" `
  -F "designer_name=Designer" `
  -F "script=Kannada" `
  -F "category=Display" `
  -F "font_file=@path/to/font.ttf"
```

## Files Created

### Backend (PHP)
- ✅ `php/config.php` - Configuration with Supabase credentials
- ✅ `php/supabase.php` - Supabase client class
- ✅ `php/upload_font.php` - Upload endpoint
- ✅ `php/get_fonts.php` - Fetch fonts endpoint

### Frontend Updates
- ✅ `designers.html` - Updated with drag-drop upload form
- ✅ `fonts.html` - Updated to fetch from API with auto-refresh

### Documentation
- ✅ `SUPABASE_SETUP.md` - Complete setup guide
- ✅ `TEST_GUIDE.md` - This quick test guide

## Next Steps

After successful testing:
1. Add more fonts through the upload form
2. Implement user authentication
3. Add image preview uploads
4. Set up payment integration
5. Create admin moderation panel
