<?php
// Test Supabase Connection
require_once 'config.php';
require_once 'supabase.php';

echo "=== Supabase Configuration Test ===\n\n";

// 1. Check configuration values
echo "1. Configuration Check:\n";
echo "   ✓ SUPABASE_URL: " . SUPABASE_URL . "\n";
echo "   ✓ SUPABASE_KEY: " . substr(SUPABASE_KEY, 0, 20) . "...\n";
echo "   ✓ SUPABASE_SERVICE_KEY: " . substr(SUPABASE_SERVICE_KEY, 0, 20) . "...\n";
echo "   ✓ FONTS_TABLE: " . FONTS_TABLE . "\n\n";

// 2. Validate URL format
echo "2. URL Format Check:\n";
if (filter_var(SUPABASE_URL, FILTER_VALIDATE_URL)) {
    echo "   ✓ URL format is valid\n";
    if (strpos(SUPABASE_URL, 'supabase.co') !== false) {
        echo "   ✓ URL is a Supabase domain\n";
    } else {
        echo "   ⚠ Warning: URL doesn't appear to be a Supabase domain\n";
    }
} else {
    echo "   ✗ Invalid URL format\n";
}
echo "\n";

// 3. Check JWT token structure
echo "3. API Key Structure Check:\n";
$anon_parts = explode('.', SUPABASE_KEY);
$service_parts = explode('.', SUPABASE_SERVICE_KEY);

if (count($anon_parts) === 3) {
    echo "   ✓ Anon key has valid JWT structure (3 parts)\n";
} else {
    echo "   ✗ Anon key doesn't appear to be a valid JWT\n";
}

if (count($service_parts) === 3) {
    echo "   ✓ Service key has valid JWT structure (3 parts)\n";
} else {
    echo "   ✗ Service key doesn't appear to be a valid JWT\n";
}
echo "\n";

// 4. Test Supabase connection
echo "4. Connection Test:\n";
try {
    $supabase = new SupabaseClient();
    echo "   ✓ SupabaseClient instantiated successfully\n";
    
    // Try to fetch from fonts table
    echo "   → Testing database query...\n";
    $result = $supabase->select(FONTS_TABLE . '?limit=1');
    
    if ($result['status'] === 200) {
        echo "   ✓ Database connection successful!\n";
        echo "   ✓ 'fonts' table is accessible\n";
        
        if (isset($result['data']) && is_array($result['data'])) {
            $count = count($result['data']);
            echo "   ℹ Found $count font(s) in database\n";
        }
    } elseif ($result['status'] === 404) {
        echo "   ⚠ Warning: 'fonts' table not found\n";
        echo "   → Please run the SQL schema from SUPABASE_SETUP.md\n";
    } elseif ($result['status'] === 401 || $result['status'] === 403) {
        echo "   ✗ Authentication failed\n";
        echo "   → Check if your API keys are correct\n";
    } else {
        echo "   ⚠ Unexpected response (Status: " . $result['status'] . ")\n";
        echo "   Response: " . json_encode($result) . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 5. Check PHP extensions
echo "5. PHP Environment Check:\n";
if (extension_loaded('curl')) {
    echo "   ✓ cURL extension is loaded\n";
} else {
    echo "   ✗ cURL extension is NOT loaded (required!)\n";
}

if (extension_loaded('json')) {
    echo "   ✓ JSON extension is loaded\n";
} else {
    echo "   ✗ JSON extension is NOT loaded (required!)\n";
}
echo "\n";

// 6. File upload directory check
echo "6. Upload Directory Check:\n";
if (file_exists(UPLOAD_DIR)) {
    echo "   ✓ Upload directory exists: " . UPLOAD_DIR . "\n";
    if (is_writable(UPLOAD_DIR)) {
        echo "   ✓ Upload directory is writable\n";
    } else {
        echo "   ⚠ Upload directory is NOT writable\n";
    }
} else {
    echo "   ⚠ Upload directory doesn't exist yet (will be created on first upload)\n";
}
echo "\n";

echo "=== Test Complete ===\n";
echo "\nNext Steps:\n";
echo "1. If database connection failed, run the SQL schema from SUPABASE_SETUP.md\n";
echo "2. Create the 'fonts' storage bucket in Supabase dashboard\n";
echo "3. Test upload: http://localhost:8000/designers.html\n";
echo "4. View fonts: http://localhost:8000/fonts.html\n";
?>
