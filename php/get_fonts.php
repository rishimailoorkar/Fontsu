<?php
// Disable all output buffering and warnings
error_reporting(0);
ini_set('display_errors', 0);

// Clean any previous output
if (ob_get_level()) {
    ob_end_clean();
}

require_once 'config.php';
require_once 'supabase.php';

// Set headers before any output
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$supabase = new SupabaseClient();

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Get query parameters
        $script = isset($_GET['script']) ? $_GET['script'] : null;
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null; // Changed from 'approved' to null
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        
        // Build query string for Supabase
        $query = '?select=*';
        
        // Add status filter only if explicitly provided
        if ($status && $status !== 'all') {
            $query .= '&status=eq.' . urlencode($status);
        }
        
        // Add script filter
        if ($script && $script !== 'all') {
            $query .= '&script=eq.' . urlencode($script);
        }
        
        // Add category filter
        if ($category && $category !== 'all') {
            $query .= '&category=eq.' . urlencode($category);
        }
        
        // Add search filter
        if ($search) {
            $query .= '&name=ilike.*' . urlencode($search) . '*';
        }
        
        // Add ordering
        $query .= '&order=created_at.desc';
        
        // Fetch fonts from Supabase using custom query
        $result = $supabase->customQuery(FONTS_TABLE, $query);
        
        if ($result['status'] === 200) {
            $fonts = $result['data'] ?? [];
            
            // Transform data to match frontend format
            $transformedFonts = array_map(function($font) {
                return [
                    'id' => intval($font['id'] ?? 0),
                    'name' => strval($font['name'] ?? 'Unknown'),
                    'designer' => strval($font['designer'] ?? 'Anonymous'),
                    'script' => strval($font['script'] ?? 'Kannada'),
                    'category' => strval($font['category'] ?? 'Display'),
                    'price' => intval($font['price_personal'] ?? 0),
                    'pricePersonal' => intval($font['price_personal'] ?? 0),
                    'priceCommercial' => intval($font['price_commercial'] ?? 0),
                    'preview' => strval($font['name'] ?? 'Font'),
                    'glyphs' => intval($font['glyphs'] ?? 0),
                    'status' => strval($font['status'] ?? 'pending'),
                    'fileUrl' => strval($font['file_url'] ?? ''),
                    'fileName' => strval($font['file_name'] ?? ''),
                    'fileExtension' => strval($font['file_extension'] ?? '')
                ];
            }, $fonts);
            
            $response = [
                'success' => true,
                'count' => count($transformedFonts),
                'data' => $transformedFonts
            ];
            
            // Send clean JSON response
            die(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            
        } else {
            throw new Exception('Failed to fetch fonts from database');
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        die(json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'data' => []
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
} else {
    http_response_code(405);
    die(json_encode([
        'success' => false,
        'message' => 'Method not allowed',
        'data' => []
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
?>
