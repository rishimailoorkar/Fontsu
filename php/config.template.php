<?php
// Supabase Configuration Template
// Copy this file to config.php and fill in your actual values

// Supabase Configuration
define('SUPABASE_URL', 'YOUR_SUPABASE_URL'); // Example: https://xxxxxxxxxxxxx.supabase.co
define('SUPABASE_KEY', 'YOUR_SUPABASE_ANON_KEY'); // Your anon/public key
define('SUPABASE_SERVICE_KEY', 'YOUR_SUPABASE_SERVICE_KEY'); // Your service role key (keep secret!)

// Database table name
define('FONTS_TABLE', 'fonts');

// File upload settings
define('UPLOAD_DIR', __DIR__ . '/uploads/fonts/');
define('MAX_FILE_SIZE', 10485760); // 10MB in bytes
define('ALLOWED_EXTENSIONS', ['ttf', 'otf', 'woff', 'woff2']);

// Create upload directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// CORS Headers for API calls
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
