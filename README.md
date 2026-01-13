# 🎨 Namma Fontsu - Indian Regional Typeface Marketplace

A modern web platform for discovering, previewing, and licensing authentic Indian regional typefaces. Built with vanilla HTML, CSS (Tailwind), JavaScript, and PHP with Supabase backend.

## ✨ Features

### For Users
- 🔍 **Browse Fonts**: Explore fonts by script (Kannada, Devanagari, Bengali, Tamil)
- 👁️ **Live Preview**: Test fonts with custom text in real-time
- 🛒 **Shopping Cart**: Add fonts to cart for easy checkout
- 🌐 **Translation Tool**: English to Kannada translator on the homepage
- 💳 **Dual Licensing**: Personal and commercial license options

### For Designers
- 📤 **Easy Upload**: Drag-and-drop font file uploads
- 📊 **Analytics Dashboard**: Track revenue and downloads
- ⚡ **Real-time Updates**: Fonts appear immediately after approval
- 💰 **Custom Pricing**: Set your own personal and commercial prices

### Admin Features
- ✅ **Moderation**: Approve/reject submitted fonts via Supabase dashboard
- 📦 **Cloud Storage**: All fonts stored securely in Supabase Storage
- 🔒 **Row-Level Security**: Protected database access with RLS policies

## 🚀 Quick Start

### Prerequisites
- PHP 7.4+ (with cURL extension)
- A Supabase account ([supabase.com](https://supabase.com))
- Modern web browser

### Installation

1. **Clone or download this project**
   ```bash
   cd "c:\Users\mailo\Downloads\Namma Fontsu"
   ```

2. **Configure Supabase**
   - Follow the complete guide in `SUPABASE_SETUP.md`
   - Create database table and storage bucket
   - Get your API keys

3. **Set up configuration**
   ```bash
   # Copy template to config.php
   copy php\config.template.php php\config.php
   ```
   
   Then edit `php/config.php` with your Supabase credentials:
   ```php
   define('SUPABASE_URL', 'https://your-project.supabase.co');
   define('SUPABASE_KEY', 'your-anon-key');
   define('SUPABASE_SERVICE_KEY', 'your-service-key');
   ```

4. **Start local server**
   ```bash
   php -S localhost:8000
   ```

5. **Open in browser**
   ```
   http://localhost:8000
   ```

## 📁 Project Structure

```
Namma Fontsu/
├── index.html              # Home page with translator
├── fonts.html              # Font marketplace
├── designers.html          # Designer dashboard & upload
├── about.html              # About page
├── login.html              # Login/signup page
├── css/
│   ├── styles.css          # Global styles
│   ├── home.css            # Home page styles
│   ├── fonts.css           # Font page styles
│   ├── designers.css       # Designer page styles
│   └── login.css           # Login page styles
├── js/
│   └── app.js              # Shared JavaScript (cart, font database)
├── php/
│   ├── config.php          # Supabase configuration (gitignored)
│   ├── config.template.php # Configuration template
│   ├── supabase.php        # Supabase client class
│   ├── upload_font.php     # Font upload endpoint
│   ├── get_fonts.php       # Fetch fonts endpoint
│   └── uploads/            # Temporary upload directory
├── SUPABASE_SETUP.md       # Complete Supabase setup guide
├── TEST_GUIDE.md           # Quick testing instructions
└── .gitignore              # Protect sensitive files
```

## 🎯 Core Technologies

- **Frontend**: HTML5, Tailwind CSS 3.x, Vanilla JavaScript
- **Backend**: PHP 7.4+ with cURL
- **Database**: Supabase (PostgreSQL)
- **Storage**: Supabase Storage
- **Icons**: Font Awesome 6.4.0
- **Charts**: Chart.js
- **Fonts**: Google Fonts (Noto Sans family)

## 🔌 API Endpoints

### Upload Font
```
POST /php/upload_font.php
Content-Type: multipart/form-data

Parameters:
- font_name: string (required)
- designer_name: string (required)
- script: string (required)
- category: string (optional, default: Display)
- price_personal: integer (optional, default: 499)
- price_commercial: integer (optional, default: 2499)
- description: string (optional)
- font_file: file (required, max 10MB, TTF/OTF/WOFF/WOFF2)
```

### Get Fonts
```
GET /php/get_fonts.php?status=approved&script=Kannada

Query Parameters:
- status: string (approved|pending|rejected)
- script: string (Kannada|Devanagari|Bengali|Tamil)
- category: string (Display|Text|Handwritten|Decorative)
- search: string (search by name)
```

## 🎨 Design Features

- **Raw Brutalist Aesthetic**: Bold borders, sharp corners, strong contrast
- **Texture Overlay**: Subtle grain effect for organic feel
- **Custom Shadows**: Hand-drawn shadow effects (`shadow-raw`)
- **Responsive Design**: Mobile-first approach with Tailwind breakpoints
- **Dynamic Previews**: Real-time font rendering with custom text
- **Smooth Animations**: Hover effects and transitions

## 📊 Database Schema

### Fonts Table
```sql
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
```

## 🔒 Security Features

- **Row-Level Security (RLS)**: Only approved fonts visible to public
- **File Validation**: Extension and size checks
- **CORS Protection**: Configured headers for API security
- **Input Sanitization**: PHP filters for all user inputs
- **.gitignore**: Protects sensitive config files

## 🧪 Testing

See `TEST_GUIDE.md` for detailed testing instructions.

Quick test:
```bash
# Start server
php -S localhost:8000

# Upload a font
# Open http://localhost:8000/designers.html

# View fonts
# Open http://localhost:8000/fonts.html
```

## 📝 Development Roadmap

- [ ] User authentication (Supabase Auth)
- [ ] Payment integration (Razorpay/Stripe)
- [ ] Font preview images
- [ ] Download tracking
- [ ] Designer revenue dashboard
- [ ] Admin moderation panel
- [ ] Email notifications
- [ ] Font file analysis (glyph counting)
- [ ] Advanced search & filters
- [ ] Wishlist functionality

## 🤝 Contributing

This is a learning project. Feel free to:
- Report bugs
- Suggest features
- Improve documentation
- Optimize code

## 📄 License

This project is for educational purposes. Font files uploaded by users are subject to their respective licenses.

## 🙏 Acknowledgments

- **Typeface Inspiration**: Indian regional font foundries
- **Design System**: Brutalist web design principles
- **Translation API**: MyMemory & LibreTranslate
- **Icons**: Font Awesome
- **Fonts**: Google Fonts (Noto Sans family)

## 📞 Support

For setup issues:
1. Check `SUPABASE_SETUP.md` for configuration
2. Review `TEST_GUIDE.md` for troubleshooting
3. Verify PHP and cURL are installed
4. Check browser console for errors

---

**Built with ❤️ for Indian typography**
