<?php

// Use CodeIgniter's database connection
require_once 'vendor/codeigniter4/framework/system/bootstrap.php';

echo "<h1>CodeIgniter Database Debug</h1>";

try {
    // Get database connection
    $db = \Config\Database::connect();
    echo "✓ CodeIgniter database connection successful<br>";

    // Test query
    $query = $db->query("SELECT COUNT(*) as count FROM users");
    $result = $query->getRow();
    echo "✓ Query successful<br>";
    echo "Current users count: " . $result->count . "<br>";

    // Test insert
    echo "<h3>Test Insert:</h3>";
    $testData = [
        'email' => 'test' . time() . '@example.com',
        'password' => md5('123456' . 'testsalt'),
        'phone_number' => '+628' . time(),
        'salt' => 'testsalt'
    ];

    echo "Test data: " . json_encode($testData) . "<br>";

    $result = $db->table('users')->insert($testData);

    if ($result) {
        echo "✓ Insert successful<br>";
        
        // Verify insert
        $query = $db->query("SELECT * FROM users WHERE email = ?", [$testData['email']]);
        $inserted = $query->getRow();
        
        if ($inserted) {
            echo "✓ Data verified in database<br>";
            echo "Inserted ID: " . $inserted->id . "<br>";
            
            // Clean up
            $db->table('users')->where('id', $inserted->id)->delete();
            echo "✓ Test data cleaned up<br>";
        } else {
            echo "✗ Data not found after insert<br>";
        }
    } else {
        echo "✗ Insert failed<br>";
        echo "Database error: " . print_r($db->error(), true) . "<br>";
    }

    // Test UserModel
    echo "<h3>Test UserModel:</h3>";
    $userModel = new \App\Models\UserModel();
    
    $testData2 = [
        'email' => 'test2' . time() . '@example.com',
        'password' => md5('123456' . 'testsalt2'),
        'phone_number' => '+628' . (time() + 1),
        'salt' => 'testsalt2'
    ];

    $result = $userModel->insert($testData2);

    if ($result) {
        echo "✓ UserModel insert successful, ID: " . $result . "<br>";
        
        // Clean up
        $userModel->delete($result);
        echo "✓ Test data cleaned up<br>";
    } else {
        echo "✗ UserModel insert failed<br>";
        echo "Model errors: " . print_r($userModel->errors(), true) . "<br>";
    }

} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
}

echo "<h3>Debug Complete</h3>";

?>
