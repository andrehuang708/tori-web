<?php

// Include CodeIgniter
require_once 'vendor/codeigniter4/framework/system/bootstrap.php';

// Initialize database
$db = \Config\Database::connect();

echo "<h1>Registration Debug Test</h1>";

// Test 1: Check database connection
echo "<h2>1. Database Connection Test</h2>";
try {
    $query = $db->query("SELECT COUNT(*) as count FROM users");
    $result = $query->getRow();
    echo "✓ Database connected successfully<br>";
    echo "✓ Current users count: " . $result->count . "<br>";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "<br>";
    die();
}

// Test 2: Test direct insert
echo "<h2>2. Direct Insert Test</h2>";
try {
    $testData = [
        'email' => 'test' . time() . '@example.com',
        'password' => md5('123456' . 'testsalt'),
        'phone_number' => '+628999999999',
        'salt' => 'testsalt'
    ];
    
    echo "Test data: " . json_encode($testData) . "<br>";
    
    $result = $db->table('users')->insert($testData);
    
    if ($result) {
        echo "✓ Insert successful<br>";
        
        // Verify the insert
        $query = $db->query("SELECT * FROM users WHERE email = ?", [$testData['email']]);
        $inserted = $query->getRow();
        
        if ($inserted) {
            echo "✓ Data verified in database<br>";
            echo "Inserted ID: " . $inserted->id . "<br>";
            
            // Clean up test data
            $db->table('users')->where('id', $inserted->id)->delete();
            echo "✓ Test data cleaned up<br>";
        } else {
            echo "✗ Data not found after insert<br>";
        }
    } else {
        echo "✗ Insert failed<br>";
        echo "Database error: " . print_r($db->error(), true) . "<br>";
    }
} catch (Exception $e) {
    echo "✗ Insert exception: " . $e->getMessage() . "<br>";
}

// Test 3: Test UserModel
echo "<h2>3. UserModel Test</h2>";
try {
    $userModel = new \App\Models\UserModel();
    
    $testData2 = [
        'email' => 'test2' . time() . '@example.com',
        'password' => md5('123456' . 'testsalt2'),
        'phone_number' => '+628888888888',
        'salt' => 'testsalt2'
    ];
    
    echo "Test data: " . json_encode($testData2) . "<br>";
    
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
    echo "✗ UserModel exception: " . $e->getMessage() . "<br>";
}

// Test 4: Check form submission simulation
echo "<h2>4. Form Submission Simulation</h2>";
try {
    // Simulate POST data
    $_POST = [
        'email' => 'test3' . time() . '@example.com',
        'password' => '123456',
        'phone_number' => '+628777777777',
        'confirm_password' => '123456'
    ];
    
    echo "Simulated POST data: " . json_encode($_POST) . "<br>";
    
    // Test validation rules
    $rules = [
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'phone_number' => 'required|min_length[10]|max_length[20]',
        'confirm_password' => 'required|matches[password]'
    ];
    
    $validation = \Config\Services::validation();
    $validation->setRules($rules);
    
    if ($validation->run($_POST)) {
        echo "✓ Validation passed<br>";
        
        $salt = md5(uniqid(rand(), true));
        $hashedPassword = md5($_POST['password'] . $salt);
        
        $userData = [
            'email' => $_POST['email'],
            'password' => $hashedPassword,
            'phone_number' => $_POST['phone_number'],
            'salt' => $salt
        ];
        
        echo "User data prepared: " . json_encode($userData) . "<br>";
        
        $result = $db->table('users')->insert($userData);
        
        if ($result) {
            echo "✓ Full registration test successful<br>";
            
            // Clean up
            $db->table('users')->where('email', $_POST['email'])->delete();
            echo "✓ Test data cleaned up<br>";
        } else {
            echo "✗ Full registration test failed<br>";
        }
    } else {
        echo "✗ Validation failed<br>";
        echo "Validation errors: " . json_encode($validation->getErrors()) . "<br>";
    }
} catch (Exception $e) {
    echo "✗ Form simulation exception: " . $e->getMessage() . "<br>";
}

echo "<h2>Debug Complete</h2>";
echo "<p>Check the results above to identify the issue.</p>";

?>
