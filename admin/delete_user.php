<?php
// admin/delete_user.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: adminportal.php");
    exit();
}
include '../connection.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete the user from the users table.
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $user_id]);

    // OPTIONAL: Reset the auto-increment counter (Note: this does not rearrange IDs for existing rows)
    // Uncomment the following lines if you really want to reset the auto-increment value.
    /*
    $sqlReset = "ALTER TABLE users AUTO_INCREMENT = 1";
    $conn->exec($sqlReset);
    */

    header("Location: registered_users.php");
    exit();
} else {
    die("No user ID specified.");
}
?>
