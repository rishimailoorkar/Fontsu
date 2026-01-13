<?php
require_once 'config.php';
require_once 'supabase.php';

$supabase = new SupabaseClient();

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

try {
    // Validate required fields
    if (!isset($input['id']) || !isset($input['status'])) {
        throw new Exception('Missing required fields: id and status');
    }
    
    $fontId = intval($input['id']);
    $newStatus = trim($input['status']);
    
    // Validate status value
    $allowedStatuses = ['pending', 'approved', 'rejected'];
    if (!in_array($newStatus, $allowedStatuses)) {
        throw new Exception('Invalid status. Allowed values: pending, approved, rejected');
    }
    
    // Update the font status
    $updateData = [
        'status' => $newStatus,
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $result = $supabase->update(FONTS_TABLE, $updateData, ['id' => $fontId]);
    
    if ($result['status'] === 200 || $result['status'] === 204) {
        echo json_encode([
            'success' => true,
            'message' => "Font status updated to {$newStatus}",
            'data' => $result['data']
        ]);
    } else {
        throw new Exception('Failed to update font: ' . json_encode($result));
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
