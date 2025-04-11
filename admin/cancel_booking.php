<?php
// admin/cancel_booking.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: adminportal.php");
    exit();
}

include '../connection.php';

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];
    
    // Retrieve booking details (number of tickets and movie id)
    $sqlSelect = "SELECT movie_id, num_tickets FROM bookings WHERE id = :id";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->execute([':id' => $booking_id]);
    $booking = $stmtSelect->fetch(PDO::FETCH_ASSOC);
    
    if ($booking) {
        $movie_id = $booking['movie_id'];
        $num_tickets = $booking['num_tickets'];
        
        // Update the available seats by adding back the cancelled tickets
        $sqlUpdate = "UPDATE movies SET seats = seats + :num_tickets WHERE id = :movie_id";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':num_tickets' => $num_tickets,
            ':movie_id'    => $movie_id
        ]);
        
        // Now cancel (delete) the booking
        $sqlDelete = "DELETE FROM bookings WHERE id = :id";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->execute([':id' => $booking_id]);
    }
    header("Location: dashboard.php");
    exit();
} else {
    die("No booking ID specified.");
}
?>
