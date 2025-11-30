<?php

// Simple database test
$host = '127.0.0.1';
$user = 'root';
$pass = 'mysql';
$dbname = 'torishop';

echo "<h1>Simple Database Debug</h1>";

// Test connection
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "✓ Database connected successfully<br>";

// Test table exists
$result = $conn->query("DESCRIBE users");
if ($result) {
    echo "✓ Users table exists<br>";
    
    // Show table structure
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    die("Users table doesn't exist");
}

// Test insert
echo "<h3>Test Insert:</h3>";
$testEmail = 'test' . time() . '@example.com';
$testPhone = '+628' . time();

$sql = "INSERT INTO users (email, password, phone_number, salt) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$password = md5('123456' . 'testsalt');
$stmt->bind_param("ssss", $testEmail, $password, $testPhone, 'testsalt');

if ($stmt->execute()) {
    echo "✓ Insert successful<br>";
    echo "Inserted ID: " . $conn->insert_id . "<br>";
    
    // Verify insert
    $result = $conn->query("SELECT * FROM users WHERE email = '$testEmail'");
    if ($result->num_rows > 0) {
        echo "✓ Data verified in database<br>";
        $row = $result->fetch_assoc();
        echo "Email: " . $row['email'] . "<br>";
        echo "Phone: " . $row['phone_number'] . "<br>";
        
        // Clean up
        $conn->query("DELETE FROM users WHERE email = '$testEmail'");
        echo "✓ Test data cleaned up<br>";
    } else {
        echo "✗ Data not found after insert<br>";
    }
} else {
    echo "✗ Insert failed: " . $stmt->error . "<br>";
}

$stmt->close();
$conn->close();

echo "<h3>Debug Complete</h3>";

?>
