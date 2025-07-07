<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Login System</h2>";

// Include config
require_once 'sw-library/sw-config.php';

echo "<h3>1. Database Connection Test</h3>";
if ($connection->connect_error) {
    echo "<p style='color:red'>❌ Database connection failed: " . $connection->connect_error . "</p>";
    exit;
} else {
    echo "<p style='color:green'>✅ Database connected successfully</p>";
}

echo "<h3>2. Check User Table</h3>";
$query = "SELECT * FROM user";
$result = $connection->query($query);

if (!$result) {
    echo "<p style='color:red'>❌ Error querying user table: " . $connection->error . "</p>";
    exit;
}

echo "<p>📊 Found " . $result->num_rows . " users in database:</p>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Fullname</th><th>Level</th><th>Password Hash (first 20 chars)</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['user_id'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['fullname'] . "</td>";
    echo "<td>" . $row['level'] . "</td>";
    echo "<td>" . substr($row['password'], 0, 20) . "...</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>3. Test Password Hashing</h3>";
$salt = '$%DSuTyr47542@#&*!=QxR094{a911}+';
$test_password = 'eydcom';
$hashed = hash('sha256', $salt . $test_password);

echo "<p><strong>Salt:</strong> " . $salt . "</p>";
echo "<p><strong>Test Password:</strong> " . $test_password . "</p>";
echo "<p><strong>Generated Hash:</strong> " . $hashed . "</p>";

echo "<h3>4. Check if Hash Matches Database</h3>";
$query_check = "SELECT * FROM user WHERE password = '$hashed'";
$result_check = $connection->query($query_check);

if ($result_check && $result_check->num_rows > 0) {
    echo "<p style='color:green'>✅ Password hash matches! Found " . $result_check->num_rows . " user(s)</p>";
    while ($row = $result_check->fetch_assoc()) {
        echo "<p>👤 User: " . $row['username'] . " (" . $row['fullname'] . ")</p>";
    }
} else {
    echo "<p style='color:red'>❌ Password hash does not match any user</p>";
}

echo "<h3>5. Test Login Process</h3>";
$test_username = 'eydcom.com';
$test_password = 'eydcom';
$hashed_password = hash('sha256', $salt . $test_password);

echo "<p><strong>Testing with:</strong></p>";
echo "<p>Username: " . $test_username . "</p>";
echo "<p>Password: " . $test_password . "</p>";
echo "<p>Hashed: " . $hashed_password . "</p>";

$login_query = "SELECT * FROM user WHERE username='$test_username' AND password='$hashed_password'";
echo "<p><strong>SQL Query:</strong> " . $login_query . "</p>";

$login_result = $connection->query($login_query);
if ($login_result && $login_result->num_rows > 0) {
    echo "<p style='color:green'>✅ Login would be successful!</p>";
    $user_data = $login_result->fetch_assoc();
    echo "<p>User data: " . json_encode($user_data) . "</p>";
} else {
    echo "<p style='color:red'>❌ Login would fail</p>";
    echo "<p>Error: " . $connection->error . "</p>";
}

echo "<h3>6. Recommendations</h3>";
echo "<p>Try these credentials:</p>";
echo "<ul>";
echo "<li><strong>Username:</strong> eydcom.com</li>";
echo "<li><strong>Password:</strong> eydcom</li>";
echo "</ul>";
echo "<p>Or:</p>";
echo "<ul>";
echo "<li><strong>Username:</strong> operator</li>";
echo "<li><strong>Password:</strong> eydcom</li>";
echo "</ul>";
?>
