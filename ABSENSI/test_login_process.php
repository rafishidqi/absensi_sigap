<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Test Login Process</h2>";

// Include config
require_once 'sw-library/sw-config.php';
include_once 'sw-library/sw-function.php';

// Simulasi data yang dikirim dari form login
$test_username = 'eydcom.com';
$test_password = 'eydcom';
$salt = '$%DSuTyr47542@#&*!=QxR094{a911}+';

echo "<h3>1. Input Data</h3>";
echo "<p><strong>Username:</strong> " . $test_username . "</p>";
echo "<p><strong>Password:</strong> " . $test_password . "</p>";
echo "<p><strong>Salt:</strong> " . $salt . "</p>";

// Proses hashing seperti di login-proses.php
$username = htmlentities($test_username);
$password = hash('sha256', $salt . $test_password);

echo "<h3>2. Processed Data</h3>";
echo "<p><strong>Processed Username:</strong> " . $username . "</p>";
echo "<p><strong>Hashed Password:</strong> " . $password . "</p>";

// Query seperti di login-proses.php
$query_login = "SELECT * FROM user WHERE username='$username' AND password='$password'";
echo "<h3>3. SQL Query</h3>";
echo "<p><code>" . $query_login . "</code></p>";

// Eksekusi query
$result_login = $connection->query($query_login);
$login_num = $result_login->num_rows;

echo "<h3>4. Query Result</h3>";
echo "<p><strong>Number of rows found:</strong> " . $login_num . "</p>";

if ($login_num > 0) {
    echo "<p style='color:green'>✅ User found! Login should be successful.</p>";
    $row = $result_login->fetch_assoc();
    echo "<p><strong>User data:</strong></p>";
    echo "<pre>" . print_r($row, true) . "</pre>";
    
    // Simulasi response sukses
    echo "<h3>5. Expected Response</h3>";
    echo "<p style='color:green'>JSON Response: <code>{\"response\":{\"error\": \"1\"}}</code> (Success)</p>";
} else {
    echo "<p style='color:red'>❌ No user found! This is why login fails.</p>";
    
    // Debug: Cek semua user yang ada
    echo "<h3>5. Debug: All Users in Database</h3>";
    $all_users_query = "SELECT user_id, username, email, fullname, level, LEFT(password, 20) as password_preview FROM user";
    $all_users_result = $connection->query($all_users_query);
    
    if ($all_users_result && $all_users_result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Fullname</th><th>Level</th><th>Password (first 20 chars)</th></tr>";
        while ($user_row = $all_users_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $user_row['user_id'] . "</td>";
            echo "<td>" . $user_row['username'] . "</td>";
            echo "<td>" . $user_row['email'] . "</td>";
            echo "<td>" . $user_row['fullname'] . "</td>";
            echo "<td>" . $user_row['level'] . "</td>";
            echo "<td>" . $user_row['password_preview'] . "...</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red'>❌ No users found in database!</p>";
    }
    
    // Test dengan berbagai kemungkinan password
    echo "<h3>6. Testing Different Password Combinations</h3>";
    $test_passwords = ['eydcom', 'admin', '123456', 'password'];
    
    foreach ($test_passwords as $test_pass) {
        $test_hash = hash('sha256', $salt . $test_pass);
        $test_query = "SELECT username, fullname FROM user WHERE username='$username' AND password='$test_hash'";
        $test_result = $connection->query($test_query);
        
        if ($test_result && $test_result->num_rows > 0) {
            echo "<p style='color:green'>✅ Found match with password: <strong>$test_pass</strong></p>";
        } else {
            echo "<p style='color:red'>❌ No match with password: $test_pass</p>";
        }
    }
    
    // Expected response untuk gagal
    echo "<h3>7. Expected Response</h3>";
    echo "<p style='color:red'>JSON Response: <code>{\"response\":{\"error\": \"0\"}}</code> (Failed)</p>";
}

// Test koneksi database
echo "<h3>8. Database Connection Test</h3>";
if ($connection->connect_error) {
    echo "<p style='color:red'>❌ Database connection failed: " . $connection->connect_error . "</p>";
} else {
    echo "<p style='color:green'>✅ Database connection successful</p>";
    echo "<p>Database: " . DB_NAME . "</p>";
    echo "<p>Host: " . DB_HOST . "</p>";
    echo "<p>User: " . DB_USER . "</p>";
}

// Cek apakah tabel user ada
echo "<h3>9. Table Structure Check</h3>";
$table_check = $connection->query("DESCRIBE user");
if ($table_check) {
    echo "<p style='color:green'>✅ User table exists</p>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($field = $table_check->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $field['Field'] . "</td>";
        echo "<td>" . $field['Type'] . "</td>";
        echo "<td>" . $field['Null'] . "</td>";
        echo "<td>" . $field['Key'] . "</td>";
        echo "<td>" . $field['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>❌ User table does not exist!</p>";
}

echo "<h3>10. Recommendations</h3>";
if ($login_num == 0) {
    echo "<div style='background-color: #fff3cd; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>🔧 To fix this issue:</strong></p>";
    echo "<ol>";
    echo "<li>Run the setup script: <a href='setup_admin.php'>setup_admin.php</a></li>";
    echo "<li>Or manually import the database SQL file</li>";
    echo "<li>Make sure the user table has the correct data</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div style='background-color: #d4edda; padding: 15px; border-radius: 5px;'>";
    echo "<p><strong>✅ Login should work with:</strong></p>";
    echo "<p>Username: <code>eydcom.com</code></p>";
    echo "<p>Password: <code>eydcom</code></p>";
    echo "</div>";
}
?>
