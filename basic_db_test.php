<?php

echo "<h1>Basic Database Test</h1>";

// Test 1: Check if MySQL extension is loaded
if (!extension_loaded('mysqli') && !extension_loaded('pdo_mysql')) {
    echo "✗ MySQL extensions not loaded<br>";
    echo "mysqli: " . (extension_loaded('mysqli') ? 'yes' : 'no') . "<br>";
    echo "pdo_mysql: " . (extension_loaded('pdo_mysql') ? 'yes' : 'no') . "<br>";
    die();
} else {
    echo "✓ MySQL extensions loaded<br>";
    echo "mysqli: " . (extension_loaded('mysqli') ? 'yes' : 'no') . "<br>";
    echo "pdo_mysql: " . (extension_loaded('pdo_mysql') ? 'yes' : 'no') . "<br>";
}

// Test 2: Try different connection methods
$hosts = ['localhost', '127.0.0.1', 'localhost:3306', '127.0.0.1:3306'];
$user = 'root';
$pass = 'mysql';
$dbname = 'torishop';

foreach ($hosts as $host) {
    echo "<h3>Testing host: $host</h3>";
    
    try {
        $conn = new mysqli($host, $user, $pass, $dbname);
        
        if ($conn->connect_error) {
            echo "✗ Connection failed: " . $conn->connect_error . "<br>";
        } else {
            echo "✓ Connection successful<br>";
            
            // Test query
            $result = $conn->query("SELECT COUNT(*) as count FROM users");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "✓ Query successful, users count: " . $row['count'] . "<br>";
                
                // Test insert
                $testEmail = 'basic_test_' . time() . '@example.com';
                $sql = "INSERT INTO users (email, password, phone_number, salt, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
                $stmt = $conn->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("ssss", $testEmail, md5('123456' . 'test'), '+628999999999', 'test');
                    
                    if ($stmt->execute()) {
                        echo "✓ Insert successful<br>";
                        
                        // Clean up
                        $conn->query("DELETE FROM users WHERE email = '$testEmail'");
                        echo "✓ Test data cleaned up<br>";
                        
                        echo "<strong>✓ This host works! Using: $host</strong><br>";
                        $conn->close();
                        break;
                    } else {
                        echo "✗ Insert failed: " . $stmt->error . "<br>";
                    }
                    $stmt->close();
                } else {
                    echo "✗ Prepare failed: " . $conn->error . "<br>";
                }
            } else {
                echo "✗ Query failed: " . $conn->error . "<br>";
            }
            
            $conn->close();
        }
    } catch (Exception $e) {
        echo "✗ Exception: " . $e->getMessage() . "<br>";
    }
}

// Test 3: Check PHP info
echo "<h3>PHP Database Info</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "mysqli Version: " . phpversion('mysqli') . "<br>";

if (extension_loaded('pdo')) {
    echo "PDO Version: " . phpversion('pdo') . "<br>";
}

echo "<h3>Test Complete</h3>";

?>
