<?php
error_reporting(0);
ini_set('display_errors', 0);

if (ob_get_level()) {
    ob_end_clean();
}

require_once 'config.php';
require_once 'supabase.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

$supabase = new SupabaseClient();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = trim($input['name'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $role = $input['role'] ?? 'buyer';
        
        // Validate input
        if (empty($name) || empty($email) || empty($password)) {
            die(json_encode([
                'success' => false,
                'message' => 'All fields are required'
            ]));
        }
        // Optional phone validation
        if (!empty($phone) && !preg_match('/^[0-9]{7,15}$/', $phone)) {
            die(json_encode([
                'success' => false,
                'message' => 'Invalid phone number'
            ]));
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die(json_encode([
                'success' => false,
                'message' => 'Invalid email format'
            ]));
        }
        
        if (strlen($password) < 6) {
            die(json_encode([
                'success' => false,
                'message' => 'Password must be at least 6 characters'
            ]));
        }
        
        // Check if user already exists
        $checkQuery = '?select=id&email=eq.' . urlencode($email);
        // Use service key to bypass RLS when checking existing user
        $existingUser = $supabase->customQueryWithServiceKey('users', $checkQuery);
        
        if ($existingUser['status'] === 200 && !empty($existingUser['data'])) {
            die(json_encode([
                'success' => false,
                'message' => 'Email already registered'
            ]));
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Create user data
        $userData = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Insert user into database
        // Use service key for inserts
        $result = $supabase->insert('users', $userData);
        
        if ($result['status'] === 201) {
            $user = $result['data'][0] ?? [];
            
            // Remove password from response
            unset($user['password']);
            
            die(json_encode([
                'success' => true,
                'message' => 'Account created successfully',
                'user' => [
                    'id' => $user['id'] ?? null,
                    'name' => $user['name'] ?? $name,
                    'email' => $user['email'] ?? $email,
                    'role' => $user['role'] ?? $role,
                    'phone' => $user['phone'] ?? $phone
                ]
            ]));
        } else {
            throw new Exception('Failed to create account');
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        die(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));
    }
} else {
    http_response_code(405);
    die(json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]));
}
?>
