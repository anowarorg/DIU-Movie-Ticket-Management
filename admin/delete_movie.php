<?php
// admin/delete_movie.php
session_start();

// Ensure that the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: adminportal.php");
    exit();
}

include '../connection.php';

// Check if a movie ID is provided via the URL
if (isset($_GET['id'])) {
    $movie_id = $_GET['id'];
    
    // Prepare and execute the SQL DELETE statement
    $sql = "DELETE FROM movies WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $movie_id]);
    
    // Redirect to the dashboard after deletion
    header("Location: dashboard.php");
    exit();
} else {
    // If no movie ID is provided, display an error message
    die("No movie ID specified.");
}
?>
