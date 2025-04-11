<?php
// booking_details.php
session_start();
include 'connection.php';

// Redirect to login if the user is not logged in.
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Prepare the query to fetch booking details for the logged-in user,
// grouping by movie so that if a user books additional tickets for the same movie,
// the total ticket count is summed.
$sql = "SELECT m.movie_name, m.show_date, m.show_time, SUM(b.num_tickets) AS num_tickets
        FROM bookings b 
        JOIN movies m ON b.movie_id = m.id 
        WHERE b.user_id = :user_id
        GROUP BY b.movie_id";
$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $_SESSION['user']]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Bookings | DIU Movie Ticket Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Global Reset and Typography */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #ffffff;
            color: #333;
        }
        /* Header Styling */
        header {
            background: linear-gradient(90deg, #3a7bd5, #3a6073);
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header-right a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        .user-icon {
            font-size: 20px;
            vertical-align: middle;
        }
        /* Content Container for Booking Details */
        .content-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background: #3a7bd5;
            color: #fff;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        tbody tr:nth-child(even) {
            background: #e9e9e9;
        }
        /* Back Button Styling */
        .back-btn {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background: #3a7bd5;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .back-btn:hover {
            background: #3a6073;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div>
            <h1>DIU Movie Ticket Management</h1>
        </div>
        <div class="header-right">
            <a href="index.php">Home</a>
            <?php 
                if (isset($_SESSION['user'])) {
                    echo '<span class="user-icon">&#128100;</span> <a href="logout.php">Logout</a>';
                } else {
                    echo '<a href="login.php">Login</a> | <a href="signup.php">Sign Up</a>';
                }
            ?>
        </div>
    </header>
    
    <!-- Content Container -->
    <div class="content-container">
        <h2>My Booking Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Movie Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Tickets Booked</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 1; // Initialize sequential counter
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($row['movie_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['show_date']); ?></td>
                    <td><?php echo date("g:i A", strtotime($row['show_time'])); ?></td>
                    <td><?php echo $row['num_tickets']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <a class="back-btn" href="index.php">Back to Home</a>
    </div>
</body>
</html>
