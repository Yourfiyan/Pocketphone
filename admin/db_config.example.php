<?php
/*
 * Database Configuration
 *
 * PLEASE UPDATE these values with your actual database credentials.
 */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'username'); // <-- IMPORTANT
define('DB_PASSWORD', 'random'); // <-- IMPORTANT
define('DB_NAME', 'dbname'); // <-- IMPORTANT

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn->connect_error){
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// Set charset to utf8mb4 for full UTF-8 support
$conn->set_charset("utf8mb4");

?>
