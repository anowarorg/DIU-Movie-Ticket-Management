<?php 
// dashboard.php (admin part)
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: adminportal.php");
    exit();
}
include '../connection.php';

// Query to fetch booking details
$sqlBookings = "SELECT b.id, u.name, u.email, m.movie_name, b.num_tickets, m.seats AS remaining_seats, m.show_time 
                FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                JOIN movies m ON b.movie_id = m.id";
$stmtBookings = $conn->query($sqlBookings);

// Query for registered users
$sqlUsers = "SELECT id, name, email, phone, address FROM users";
$stmtUsers = $conn->query($sqlUsers);

// Query to fetch all movies for the "Movies" table
$sqlMovies = "SELECT * FROM movies";
$stmtMovies = $conn->query($sqlMovies);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | DIU Movie Ticket Management</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Global Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #ffffff;
            color: #333;
        }
        /* Header styling */
        header {
            background: linear-gradient(90deg, #3a7bd5, #3a6073);
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h2 {
            margin: 0;
            font-size: 24px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            font-size: 16px;
        }
        .header-right a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        /* Content Container Styling */
        .content-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
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
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background: #e9e9e9;
        }
        /* Section Headings */
        h3 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>

<header>
    <h2>Admin Dashboard</h2>
    <nav>
        <a href="add_movie.php">Add Movies</a> |
        <a href="registered_users.php">Registered Users</a> |
        <a href="../logout.php">Logout</a>
    </nav>
</header>

<!-- Movies Section -->
<div class="content-container">
    <h3>Movies</h3>
    <table border="1">
      <tr>
        <th>ID</th>
        <th>Movie Name</th>
        <th>Seats</th>
        <th>Date</th>
        <th>Time</th>
        <th>Actions</th>
      </tr>
      <?php while ($movie = $stmtMovies->fetch(PDO::FETCH_ASSOC)) { ?>
      <tr>
         <td><?php echo $movie['id']; ?></td>
         <td><?php echo htmlspecialchars($movie['movie_name']); ?></td>
         <td><?php echo $movie['seats']; ?></td>
         <td><?php echo $movie['show_date']; ?></td>
         <td><?php echo date("g:i A", strtotime($movie['show_time'])); ?></td>
         <td>
           <a href="update_movie.php?id=<?php echo $movie['id']; ?>">Edit</a> | 
           <a href="delete_movie.php?id=<?php echo $movie['id']; ?>" onclick="return confirm('Are you sure to delete?');">Delete</a>
         </td>
      </tr>
      <?php } ?>
    </table>
</div>

<!-- Bookings Section -->
<div class="content-container">
    <h3>Bookings</h3>
    <table border="1">
      <tr>
        <th>Booking ID</th>
        <th>User Name</th>
        <th>Email</th>
        <th>Movie Name</th>
        <th>Tickets Booked</th>
        <th>Remaining Seats</th>
        <th>Action</th>
      </tr>
      <?php 
      $count = 1; // Initialize sequential counter for display purposes
      while ($booking = $stmtBookings->fetch(PDO::FETCH_ASSOC)) { ?>
      <tr>
        <!-- Display sequential counter -->
        <td><?php echo $count++; ?></td>
        <td><?php echo htmlspecialchars($booking['name']); ?></td>
        <td><?php echo htmlspecialchars($booking['email']); ?></td>
        <td><?php echo htmlspecialchars($booking['movie_name']); ?></td>
        <td><?php echo $booking['num_tickets']; ?></td>
        <td><?php echo $booking['remaining_seats']; ?></td>
        <td>
            <a href="cancel_booking.php?id=<?php echo $booking['id']; ?>" 
               onclick="return confirm('Cancel this booking?');">Cancel</a>
        </td>
      </tr>
      <?php } ?>
    </table>
</div>
</body>
</html>
