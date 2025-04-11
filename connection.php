<?php
// connection.php
$servername = "localhost";
$username   = "root";
$password   = ""; // Default for XAMPP
$dbname     = "diu_movie_ticket_management";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
