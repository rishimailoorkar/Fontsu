<?php
require_once 'config.php';
require_once 'supabase.php';

// Check if user is logged in (optional - you can make downloads require login)
// For now, we'll allow downloads if the font was purchased

if (!isset($_GET['id'])) {
    die('Font ID is required');
}

$fontId = intval($_GET['id']);
$supabase = new SupabaseClient();

try {
    // Get font details
    $result = $supabase->customQueryWithServiceKey('fonts', '?select=*&id=eq.' . $fontId);
    
    if ($result['status'] !== 200 || empty($result['data'])) {
        die('Font not found');
    }
    
    $font = $result['data'][0];
    
    // Check if font file exists
    if (empty($font['file_url'])) {
        die('Font file not available');
    }
    
    // For free fonts or purchased fonts, allow download
    // In production, you'd verify purchase from database
    
    // Get the file URL from Supabase
    $fileUrl = $font['file_url'];
    $fileName = $font['file_name'] ?? $font['name'] . '.ttf';
    
    // If file is stored in Supabase storage
    if (strpos($fileUrl, 'supabase') !== false) {
        // Download from Supabase and serve
        $fileContent = file_get_contents($fileUrl);
        
        if ($fileContent === false) {
            die('Failed to download font file');
        }
        
        // Set headers for download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . strlen($fileContent));
        header('Cache-Control: no-cache');
        
        echo $fileContent;
    } else {
        // Redirect to file URL
        header('Location: ' . $fileUrl);
    }
    
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>
