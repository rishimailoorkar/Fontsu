<?php
// Direct test of get_fonts.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Direct API Test</h1>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;} pre{background:white;padding:15px;border:2px solid #333;}</style>";

echo "<h2>Step 1: Include Files</h2>";
try {
    require_once 'php/config.php';
    echo "<p style='color:green;'>✓ config.php loaded</p>";
    
    require_once 'php/supabase.php';
    echo "<p style='color:green;'>✓ supabase.php loaded</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>✗ Error loading files: " . $e->getMessage() . "</p>";
    exit();
}

echo "<h2>Step 2: Create Supabase Client</h2>";
try {
    $supabase = new SupabaseClient();
    echo "<p style='color:green;'>✓ SupabaseClient created</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>✗ Error creating client: " . $e->getMessage() . "</p>";
    exit();
}

echo "<h2>Step 3: Build Query</h2>";
$status = 'approved';
$query = '?select=*&status=eq.' . urlencode($status) . '&order=created_at.desc';
echo "<p>Query: <code>$query</code></p>";

echo "<h2>Step 4: Execute Query</h2>";
try {
    $result = $supabase->customQuery(FONTS_TABLE, $query);
    echo "<p>Status Code: <strong>" . $result['status'] . "</strong></p>";
    
    if ($result['status'] === 200) {
        $fonts = $result['data'] ?? [];
        echo "<p style='color:green;'>✓ Query successful! Found " . count($fonts) . " fonts</p>";
        
        if (count($fonts) > 0) {
            echo "<h3>Font Data:</h3>";
            echo "<pre>" . print_r($fonts, true) . "</pre>";
            
            echo "<h3>Transformed Data (as API returns):</h3>";
            $transformedFonts = array_map(function($font) {
                return [
                    'id' => $font['id'] ?? 0,
                    'name' => $font['name'] ?? 'Unknown',
                    'designer' => $font['designer'] ?? 'Anonymous',
                    'script' => $font['script'] ?? 'Kannada',
                    'category' => $font['category'] ?? 'Display',
                    'price' => $font['price_personal'] ?? 0,
                    'pricePersonal' => $font['price_personal'] ?? 0,
                    'priceCommercial' => $font['price_commercial'] ?? 0,
                    'description' => $font['description'] ?? '',
                    'preview' => $font['name'] ?? 'ನಮ್ಮ ಫಾಂಟ್',
                    'glyphs' => $font['glyphs'] ?? 0,
                    'fileUrl' => $font['file_url'] ?? '',
                    'fileName' => $font['file_name'] ?? '',
                    'fileExtension' => $font['file_extension'] ?? '',
                    'status' => $font['status'] ?? 'pending',
                    'createdAt' => $font['created_at'] ?? ''
                ];
            }, $fonts);
            
            echo "<pre>" . print_r($transformedFonts, true) . "</pre>";
            
            echo "<h3>JSON Response (as API sends):</h3>";
            $response = [
                'success' => true,
                'count' => count($transformedFonts),
                'data' => $transformedFonts
            ];
            echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";
        } else {
            echo "<p style='color:orange;'>⚠️ No approved fonts found in database</p>";
            echo "<p>This means:</p>";
            echo "<ul>";
            echo "<li>Either no fonts have been uploaded yet</li>";
            echo "<li>Or uploaded fonts haven't been approved in admin panel</li>";
            echo "</ul>";
            echo "<p><a href='admin.html'>Go to Admin Panel</a> to approve fonts</p>";
        }
    } else {
        echo "<p style='color:red;'>✗ Query failed with status " . $result['status'] . "</p>";
        echo "<pre>" . print_r($result, true) . "</pre>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>✗ Error executing query: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Quick Links</h2>";
echo "<p><a href='php/get_fonts.php?status=approved'>View API Response (JSON)</a></p>";
echo "<p><a href='fonts.html'>Go to Fonts Page</a></p>";
echo "<p><a href='admin.html'>Go to Admin Panel</a></p>";
?>
