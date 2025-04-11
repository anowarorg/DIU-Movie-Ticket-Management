<?php
session_start();
include 'connection.php';

// Ensure the user is logged in.
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Check if movie ID is provided in GET parameters.
if (!isset($_GET['id'])) {
    die("No movie ID provided.");
}
$movie_id = $_GET['id'];

// Retrieve movie details.
$sqlMovie = "SELECT * FROM movies WHERE id = :movie_id";
$stmtMovie = $conn->prepare($sqlMovie);
$stmtMovie->execute([':movie_id' => $movie_id]);
$movie = $stmtMovie->fetch(PDO::FETCH_ASSOC);
if (!$movie) {
    die("Movie not found.");
}

// Process booking submission on POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_tickets = intval($_POST['num_tickets']);

    // Check available seats.
    $availableSeats = $movie['seats']; // current available seats stored in the movie record.
    if ($num_tickets > $availableSeats) {
        // Render an error page if user requests more tickets than are available.
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Error | DIU Movie Ticket Management</title>
            <link rel="stylesheet" href="style.css">
            <style>
                body {
                    background: #ffffff;
                    font-family: Arial, sans-serif;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .error-container {
                    max-width: 500px;
                    margin: 100px auto;
                    padding: 30px;
                    background: #f8d7da;
                    border: 1px solid #f5c6cb;
                    border-radius: 5px;
                    text-align: center;
                }
                .error-container h2 {
                    color: #721c24;
                    margin-bottom: 20px;
                }
                .error-container p {
                    font-size: 16px;
                    color: #721c24;
                    margin: 10px 0;
                }
                .error-container a {
                    display: inline-block;
                    margin-top: 20px;
                    padding: 10px 20px;
                    background: #f5c6cb;
                    color: #721c24;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                }
                .error-container a:hover {
                    background: #f1b0b7;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h2>Error</h2>
                <p>You cannot book more tickets than are available.</p>
                <p>Available: <?php echo $availableSeats; ?></p>
                <a href="index.php">Go Back</a>
            </div>
        </body>
        </html>
        <?php
        exit();
    }

    // Check if a booking already exists for this user and movie.
    $sqlCheck = "SELECT id, num_tickets FROM bookings WHERE user_id = :user_id AND movie_id = :movie_id";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->execute([
        ':user_id'  => $_SESSION['user'],
        ':movie_id' => $movie_id
    ]);
    $existingBooking = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($existingBooking) {
        // Update existing booking: add new tickets to already booked tickets.
        $newTotalTickets = $existingBooking['num_tickets'] + $num_tickets;
        $sqlUpdateBooking = "UPDATE bookings SET num_tickets = :new_total WHERE id = :booking_id";
        $stmtUpdateBooking = $conn->prepare($sqlUpdateBooking);
        $stmtUpdateBooking->execute([
            ':new_total' => $newTotalTickets,
            ':booking_id'=> $existingBooking['id']
        ]);
    } else {
        // Insert new booking if no existing record.
        $sqlInsert = "INSERT INTO bookings (user_id, movie_id, num_tickets)
                      VALUES (:user_id, :movie_id, :num_tickets)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->execute([
            ':user_id'    => $_SESSION['user'],
            ':movie_id'   => $movie_id,
            ':num_tickets'=> $num_tickets
        ]);
    }

    // Update the movie's available seats.
    $sqlUpdate = "UPDATE movies SET seats = seats - :num_tickets WHERE id = :movie_id";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->execute([
        ':num_tickets' => $num_tickets,
        ':movie_id'    => $movie_id
    ]);
    
    // Clamp seats to 0 if negative.
    $sqlClamp = "UPDATE movies SET seats = 0 WHERE id = :movie_id AND seats < 0";
    $stmtClamp = $conn->prepare($sqlClamp);
    $stmtClamp->execute([':movie_id' => $movie_id]);

    header("Location: booking_details.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Book Movie | DIU Movie Ticket Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Global Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #ffffff;
            color: #333;
        }
        /* Header Styling (consistent with index.php) */
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
        /* Movie Details Container */
        .movie-details-container {
            max-width: 450px;
            margin: 30px auto;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .movie-details-container h2 {
            margin-bottom: 15px;
            color: #333;
        }
        .movie-details-container p {
            font-size: 16px;
            margin: 8px 0;
            color: #555;
        }
        .movie-details-container img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        /* Booking Form Container Styling */
        .form-container {
            max-width: 400px;
            margin: 30px auto 50px;
            padding: 30px;
            background: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .form-container h2 {
            margin-bottom: 25px;
            color: #333;
        }
        .form-container label {
            display: block;
            text-align: left;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .form-container input[type="number"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            background: #3a7bd5;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #3a6073;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>DIU Movie Ticket Management</h1>
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
    
    <!-- Movie Details Display -->
    <div class="movie-details-container">
        <h2><?php echo htmlspecialchars($movie['movie_name']); ?></h2>
        <?php 
        // Display the movie picture if available; otherwise, display a placeholder image.
        if (!empty($movie['movie_pic'])) {
            echo '<img src="images/' . htmlspecialchars($movie['movie_pic']) . '" alt="' . htmlspecialchars($movie['movie_name']) . '">';
        } else {
            echo '<img src="images/placeholder.jpg" alt="No Image">';
        }
        ?>
        <p><strong>Available Seats:</strong> <?php echo $movie['seats']; ?></p>
        <p><strong>Date of Show:</strong> <?php echo htmlspecialchars($movie['show_date']); ?></p>
        <p><strong>Time of Show:</strong> <?php echo date("g:i A", strtotime($movie['show_time'])); ?></p>
    </div>
    
    <!-- Booking Form Container -->
    <div class="form-container">
        <h2>Book Your Ticket</h2>
        <form method="POST" action="">
            <label for="num_tickets">Number of Tickets:</label>
            <input type="number" name="num_tickets" id="num_tickets" min="1" value="1" required>
            <button type="submit">Book Ticket</button>
        </form>
    </div>
</body>
</html>
