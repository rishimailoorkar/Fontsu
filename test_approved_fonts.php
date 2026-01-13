<?php
// Simple test script to verify approved fonts are being fetched correctly
header('Content-Type: text/html; charset=utf-8');

require_once 'php/config.php';
require_once 'php/supabase.php';

echo "<h1>Testing Approved Fonts System</h1>";
echo "<style>body { font-family: Arial; padding: 20px; } table { border-collapse: collapse; width: 100%; margin-top: 20px; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #007F8C; color: white; }</style>";

$supabase = new SupabaseClient();

echo "<h2>Step 1: Testing Database Connection</h2>";
echo "<p>Supabase URL: " . SUPABASE_URL . "</p>";
echo "<p>API Key exists: " . (defined('SUPABASE_KEY') ? 'Yes ✓' : 'No ✗') . "</p>";

echo "<h2>Step 2: Fetching All Fonts</h2>";
$allFontsResult = $supabase->selectAll(FONTS_TABLE);
echo "<pre>";
echo "Status Code: " . $allFontsResult['status'] . "\n";
echo "Total Fonts: " . (isset($allFontsResult['data']) ? count($allFontsResult['data']) : 0) . "\n";
echo "</pre>";

echo "<h2>Step 3: Fetching Approved Fonts Only</h2>";
$query = '?select=*&status=eq.approved&order=created_at.desc';
$approvedResult = $supabase->customQuery(FONTS_TABLE, $query);

echo "<pre>";
echo "Status Code: " . $approvedResult['status'] . "\n";
echo "Approved Fonts: " . (isset($approvedResult['data']) ? count($approvedResult['data']) : 0) . "\n";
echo "</pre>";

if ($approvedResult['status'] === 200 && isset($approvedResult['data'])) {
    echo "<h2>Approved Fonts List</h2>";
    if (empty($approvedResult['data'])) {
        echo "<p style='color: orange;'>⚠️ No approved fonts found. Please approve fonts from the admin panel first!</p>";
    } else {
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Designer</th><th>Script</th><th>Category</th><th>Status</th><th>Price</th><th>Created</th></tr>";
        foreach ($approvedResult['data'] as $font) {
            echo "<tr>";
            echo "<td>" . $font['id'] . "</td>";
            echo "<td><strong>" . htmlspecialchars($font['name']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($font['designer']) . "</td>";
            echo "<td>" . htmlspecialchars($font['script']) . "</td>";
            echo "<td>" . htmlspecialchars($font['category']) . "</td>";
            echo "<td style='color: green; font-weight: bold;'>" . htmlspecialchars($font['status']) . "</td>";
            echo "<td>₹" . $font['price_personal'] . "</td>";
            echo "<td>" . date('Y-m-d H:i', strtotime($font['created_at'])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p style='color: red;'>✗ Error fetching approved fonts</p>";
    echo "<pre>" . print_r($approvedResult, true) . "</pre>";
}

echo "<h2>Step 4: Test API Endpoint</h2>";
echo "<p>The frontend calls: <code>php/get_fonts.php?status=approved</code></p>";
echo "<p><a href='php/get_fonts.php?status=approved' target='_blank'>Click here to test the API endpoint</a></p>";

echo "<hr>";
echo "<h2>Navigation</h2>";
echo "<p><a href='admin.html'>Go to Admin Panel</a> | <a href='fonts.html'>Go to All Fonts Page</a> | <a href='index.html'>Go to Homepage</a></p>";
?>
