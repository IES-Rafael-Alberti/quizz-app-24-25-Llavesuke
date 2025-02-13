<?php
// Start the session with specific cookie parameters for security
session_start([
    'cookie_lifetime' => 86400, // 1 day
    'cookie_secure' => true, // Only send cookie over HTTPS
    'cookie_httponly' => true, // Prevent JavaScript access to the cookie
    'cookie_samesite' => 'Strict', // Prevent the browser from sending this cookie along with cross-site requests
]);

// Database connection parameters
$servername = "db";
$username = "root";
$password = "example";
$dbname = "quiz_app";

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>