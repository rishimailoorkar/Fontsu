# Supabase Setup Guide for Namma Fontsu

This guide will help you set up your Supabase project and configure the database for real-time font uploads.

## 1. Create Supabase Account

1. Go to [https://supabase.com](https://supabase.com)
2. Sign up for a free account
3. Create a new project:
   - Organization: Create a new organization or use existing
   - Project name: `namma-fontsu`
   - Database password: Choose a strong password
   - Region: Choose the closest region to your users

## 2. Create Database Table

Once your project is ready, go to the SQL Editor and run this query:

```sql
-- Create fonts table
CREATE TABLE fonts (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    designer VARCHAR(255) NOT NULL,
    script VARCHAR(50) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price_personal INTEGER DEFAULT 499,
    price_commercial INTEGER DEFAULT 2499,
    description TEXT,
    file_url TEXT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_extension VARCHAR(10) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    glyphs INTEGER DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create index for faster queries
CREATE INDEX idx_fonts_script ON fonts(script);
CREATE INDEX idx_fonts_category ON fonts(category);
CREATE INDEX idx_fonts_status ON fonts(status);

-- Enable Row Level Security
ALTER TABLE fonts ENABLE ROW LEVEL SECURITY;

-- Create policies
-- Allow public read access for approved fonts
CREATE POLICY "Public read access for approved fonts"
ON fonts FOR SELECT
USING (status = 'approved');

-- Allow insert for authenticated users (you can modify this based on your auth setup)
CREATE POLICY "Allow insert for everyone"
ON fonts FOR INSERT
WITH CHECK (true);

-- Create function to update updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create trigger to automatically update updated_at
CREATE TRIGGER update_fonts_updated_at
BEFORE UPDATE ON fonts
FOR EACH ROW
EXECUTE FUNCTION update_updated_at_column();
```

## 3. Create Storage Bucket

1. Go to Storage in the Supabase dashboard
2. Click "Create a new bucket"
3. Bucket name: `fonts`
4. Set public bucket: **Yes** (so font files can be accessed publicly)
5. Click "Create bucket"

### Configure Storage Policies

Go to the Storage policies for the `fonts` bucket and add:

```sql
-- Allow public read access
CREATE POLICY "Public read access"
ON storage.objects FOR SELECT
USING (bucket_id = 'fonts');

-- Allow insert for everyone (you can restrict this later)
CREATE POLICY "Allow upload for everyone"
ON storage.objects FOR INSERT
WITH CHECK (bucket_id = 'fonts');
```

## 4. Get API Keys

1. Go to Project Settings > API
2. Copy the following values:
   - **Project URL**: `https://xxxxxxxxxxxxx.supabase.co`
   - **anon public key**: `eyJhbGc...` (long string)
   - **service_role key**: `eyJhbGc...` (longer string - keep this secret!)

## 5. Configure PHP Files

Open `php/config.php` and replace the placeholder values:

```php
// Replace these with your actual Supabase values
define('SUPABASE_URL', 'https://xxxxxxxxxxxxx.supabase.co');
define('SUPABASE_KEY', 'your-anon-public-key-here');
define('SUPABASE_SERVICE_KEY', 'your-service-role-key-here');
```

⚠️ **Security Note**: Never commit `config.php` with real credentials to public repositories!

## 6. Test the Setup

### Test Upload
1. Open `designers.html` in a web server (not directly from file system)
2. Fill out the upload form with test data
3. Upload a font file (TTF/OTF/WOFF)
4. Check if it appears in your Supabase database

### Test Retrieval
1. Open `fonts.html`
2. You should see fonts from both the static database and Supabase
3. The page will auto-refresh every 10 seconds to get new fonts

## 7. Local Development Setup

### Option 1: PHP Built-in Server
```bash
cd "c:\Users\mailo\Downloads\Namma Fontsu"
php -S localhost:8000
```
Then open: http://localhost:8000

### Option 2: XAMPP/WAMP
1. Install XAMPP or WAMP
2. Copy the project folder to `htdocs` (XAMPP) or `www` (WAMP)
3. Access via: http://localhost/Namma%20Fontsu/

## 8. Database Schema Overview

### Fonts Table
| Column | Type | Description |
|--------|------|-------------|
| id | BIGSERIAL | Primary key (auto-increment) |
| name | VARCHAR(255) | Font name |
| designer | VARCHAR(255) | Designer name |
| script | VARCHAR(50) | Script type (Kannada, Devanagari, etc.) |
| category | VARCHAR(50) | Font category (Display, Text, etc.) |
| price_personal | INTEGER | Personal license price in ₹ |
| price_commercial | INTEGER | Commercial license price in ₹ |
| description | TEXT | Font description |
| file_url | TEXT | Public URL of the font file |
| file_name | VARCHAR(255) | Stored filename |
| file_extension | VARCHAR(10) | File extension (ttf, otf, etc.) |
| status | VARCHAR(20) | Status: pending, approved, rejected |
| glyphs | INTEGER | Number of glyphs (optional) |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

## 9. API Endpoints

### Upload Font
- **URL**: `php/upload_font.php`
- **Method**: POST
- **Content-Type**: multipart/form-data
- **Parameters**:
  - `font_name` (required): Font name
  - `designer_name` (required): Designer name
  - `script` (required): Script type
  - `category` (optional): Category (default: Display)
  - `price_personal` (optional): Personal price (default: 499)
  - `price_commercial` (optional): Commercial price (default: 2499)
  - `description` (optional): Font description
  - `font_file` (required): Font file (TTF/OTF/WOFF/WOFF2, max 10MB)

### Get Fonts
- **URL**: `php/get_fonts.php`
- **Method**: GET
- **Query Parameters**:
  - `status` (optional): Filter by status (default: approved)
  - `script` (optional): Filter by script
  - `category` (optional): Filter by category
  - `search` (optional): Search by name

## 10. Moderation Workflow

To approve/reject uploaded fonts:

1. Go to Supabase Dashboard > Table Editor > fonts table
2. Find pending fonts
3. Change the `status` column:
   - `approved` - Font will appear on the marketplace
   - `rejected` - Font will be hidden
   - `pending` - Default status for new uploads

## 11. Troubleshooting

### Fonts not appearing
- Check if fonts have `status = 'approved'` in the database
- Verify the storage bucket is public
- Check browser console for API errors

### Upload fails
- Ensure PHP has write permissions to `php/uploads/fonts/` directory
- Check file size is under 10MB
- Verify file extension is allowed (TTF, OTF, WOFF, WOFF2)
- Check Supabase API keys are correct

### CORS errors
- Ensure `config.php` includes CORS headers
- Check that your domain is allowed in Supabase dashboard

### Storage errors
- Verify the `fonts` bucket exists and is public
- Check storage policies allow insert and select

## 12. Next Steps

- [ ] Set up user authentication for designer accounts
- [ ] Add image upload for font previews
- [ ] Implement font file analysis to count glyphs
- [ ] Add email notifications for upload status
- [ ] Create admin panel for font moderation
- [ ] Add payment integration for font purchases
- [ ] Implement download tracking and analytics

## Support

For issues or questions:
- Supabase Docs: https://supabase.com/docs
- Supabase Discord: https://discord.supabase.com/
