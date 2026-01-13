<?php
require_once 'config.php';
require_once 'supabase.php';

$supabase = new SupabaseClient();

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

try {
    // Validate required field
    if (!isset($input['id'])) {
        throw new Exception('Missing required field: id');
    }
    
    $fontId = intval($input['id']);
    
    // First, get the font details to retrieve the file info using service role key
    $fontResult = $supabase->selectWithServiceKey(FONTS_TABLE, '*', ['id' => $fontId]);
    
    if ($fontResult['status'] !== 200 || empty($fontResult['data'])) {
        throw new Exception('Font not found');
    }
    
    $font = $fontResult['data'][0];
    $fileName = $font['file_name'] ?? null;
    
    // Delete from database
    $deleteResult = $supabase->delete(FONTS_TABLE, ['id' => $fontId]);
    
    if ($deleteResult['status'] === 200 || $deleteResult['status'] === 204) {
        // Optionally delete file from storage
        if ($fileName) {
            try {
                $supabase->deleteFile('fonts', $fileName);
            } catch (Exception $e) {
                // Log error but don't fail the delete operation
                error_log('Failed to delete font file from storage: ' . $e->getMessage());
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Font deleted successfully'
        ]);
    } else {
        throw new Exception('Failed to delete font: ' . json_encode($deleteResult));
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
