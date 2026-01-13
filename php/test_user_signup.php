<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'supabase.php';

header('Content-Type: application/json; charset=utf-8');

$supabase = new SupabaseClient();

echo "Testing Supabase Connection...\n\n";

// Test 1: Check if we can connect to Supabase
echo "1. Testing connection to users table:\n";
$testQuery = '?select=*&limit=1';
$result = $supabase->customQuery('users', $testQuery);
echo "Status: " . $result['status'] . "\n";
echo "Response: " . json_encode($result['data'], JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Try to insert a test user
echo "2. Attempting to insert test user:\n";
$testUser = [
    'name' => 'Test User',
    'email' => 'test' . time() . '@example.com',
    'password' => password_hash('test123', PASSWORD_BCRYPT),
    'role' => 'buyer',
    'created_at' => date('Y-m-d H:i:s')
];

echo "Data to insert:\n";
echo json_encode($testUser, JSON_PRETTY_PRINT) . "\n\n";

$insertResult = $supabase->insert('users', $testUser);
echo "Insert Status: " . $insertResult['status'] . "\n";
echo "Insert Response: " . json_encode($insertResult['data'], JSON_PRETTY_PRINT) . "\n\n";

// Test 3: Check if user was inserted
if ($insertResult['status'] === 201 && !empty($insertResult['data'])) {
    $insertedId = $insertResult['data'][0]['id'];
    echo "3. Verifying inserted user (ID: $insertedId):\n";
    $verifyQuery = '?select=*&id=eq.' . $insertedId;
    $verifyResult = $supabase->customQuery('users', $verifyQuery);
    echo "Status: " . $verifyResult['status'] . "\n";
    echo "Response: " . json_encode($verifyResult['data'], JSON_PRETTY_PRINT) . "\n";
} else {
    echo "3. Insert failed, skipping verification\n";
    echo "Error details: " . json_encode($insertResult, JSON_PRETTY_PRINT) . "\n";
}
?>
