<?php
require_once 'config.php';
require_once 'supabase.php';

$supabase = new SupabaseClient();

try {
    // Get all fonts regardless of status (admin view)
    $result = $supabase->selectAll(FONTS_TABLE);
    
    if ($result['status'] === 200) {
        $fonts = $result['data'];
        
        // Sort by created_at descending (newest first)
        usort($fonts, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        echo json_encode([
            'success' => true,
            'data' => $fonts,
            'count' => count($fonts)
        ]);
    } else {
        throw new Exception('Failed to fetch fonts: ' . json_encode($result));
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
