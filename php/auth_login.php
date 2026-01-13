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
        
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        
        // Validate input
        if (empty($email) || empty($password)) {
            die(json_encode([
                'success' => false,
                'message' => 'Email and password are required'
            ]));
        }
        
        // Find user by email
        $query = '?select=*&email=eq.' . urlencode($email);
        // Use service key to bypass RLS on login fetch
        $result = $supabase->customQueryWithServiceKey('users', $query);
        
        if ($result['status'] === 200 && !empty($result['data'])) {
            $user = $result['data'][0];
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Remove password from response
                unset($user['password']);
                
                die(json_encode([
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ]
                ]));
            } else {
                die(json_encode([
                    'success' => false,
                    'message' => 'Invalid email or password'
                ]));
            }
        } else {
            die(json_encode([
                'success' => false,
                'message' => 'Invalid email or password'
            ]));
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
