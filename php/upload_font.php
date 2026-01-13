<?php
require_once 'config.php';
require_once 'supabase.php';

$supabase = new SupabaseClient();

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        if (!isset($_POST['font_name']) || !isset($_POST['script']) || !isset($_POST['designer_name'])) {
            throw new Exception('Missing required fields');
        }
        
        $fontName = trim($_POST['font_name']);
        $script = trim($_POST['script']);
        $designerName = trim($_POST['designer_name']);
        $category = isset($_POST['category']) ? trim($_POST['category']) : 'Display';
        $pricePersonal = isset($_POST['price_personal']) ? intval($_POST['price_personal']) : 499;
        $priceCommercial = isset($_POST['price_commercial']) ? intval($_POST['price_commercial']) : 2499;
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        
        // Validate font file upload
        if (!isset($_FILES['font_file']) || $_FILES['font_file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Font file is required');
        }
        
        $file = $_FILES['font_file'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmpName = $file['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validate file extension
        if (!in_array($fileExt, ALLOWED_EXTENSIONS)) {
            throw new Exception('Invalid file type. Only TTF, OTF, WOFF, and WOFF2 files are allowed.');
        }
        
        // Validate file size
        if ($fileSize > MAX_FILE_SIZE) {
            throw new Exception('File size exceeds maximum limit of 10MB');
        }
        
        // Generate unique filename
        $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExt;
        $uploadPath = $uniqueFileName;
        
        // Move file to temporary location
        $tempPath = UPLOAD_DIR . $uniqueFileName;
        if (!move_uploaded_file($fileTmpName, $tempPath)) {
            throw new Exception('Failed to upload file');
        }
        
        // Upload to Supabase Storage
        $storageResult = $supabase->uploadFile('fonts', $uploadPath, $tempPath);
        
        if ($storageResult['status'] !== 200 && $storageResult['status'] !== 201) {
            // Clean up temp file
            unlink($tempPath);
            throw new Exception('Failed to upload file to storage: ' . json_encode($storageResult));
        }
        
        // Get public URL
        $fileUrl = $supabase->getPublicUrl('fonts', $uploadPath);
        
        // Prepare font data for database
        $fontData = [
            'name' => $fontName,
            'designer' => $designerName,
            'script' => $script,
            'category' => $category,
            'price_personal' => $pricePersonal,
            'price_commercial' => $priceCommercial,
            'description' => $description,
            'file_url' => $fileUrl,
            'file_name' => $uniqueFileName,
            'file_extension' => $fileExt,
            'status' => 'pending', // Pending review
            'created_at' => date('Y-m-d H:i:s'),
            'glyphs' => 0 // Will be updated later
        ];
        
        // Insert into Supabase database
        $result = $supabase->insert(FONTS_TABLE, $fontData);
        
        // Clean up temp file
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
        
        if ($result['status'] === 201 || $result['status'] === 200) {
            echo json_encode([
                'success' => true,
                'message' => 'Font uploaded successfully and submitted for review!',
                'data' => $result['data'][0] ?? $result['data']
            ]);
        } else {
            throw new Exception('Failed to save font data: ' . json_encode($result));
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}
?>
