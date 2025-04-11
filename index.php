<?php
session_start();
include 'connection.php';

// ***** Cleanup unused images *****
// Retrieve all used image file names from the movies table.
$stmtCleanup = $conn->query("SELECT movie_pic FROM movies WHERE movie_pic IS NOT NULL AND movie_pic <> ''");
$usedImages = array();
while ($row = $stmtCleanup->fetch(PDO::FETCH_ASSOC)) {
    $usedImages[] = $row['movie_pic'];
}
$usedImages = array_unique($usedImages);

// Define images you always want to keep (for example, the placeholder image)
$alwaysKeep = array('placeholder.jpg');

// Define the images directory
$imagesDir = __DIR__ . '/images/'; // Adjust the path if necessary

if (is_dir($imagesDir)) {
    // Get an array of all files in the directory.
    $files = scandir($imagesDir);
    foreach ($files as $file) {
        // Skip the current and parent directory entries.
        if ($file == '.' || $file == '..') {
            continue;
        }
        // Delete files not in use and not in the always-keep list.
        if (!in_array($file, $usedImages) && !in_array($file, $alwaysKeep)) {
            $filePath = $imagesDir . $file;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
    }
}
// ***** End cleanup unused images *****

// Get current date and time for additional filtering if needed (optional)
// $currentDate = date("Y-m-d");
// $currentTime = date("H:i:s");

// Retrieve movies – if a search query is provided, filter by movie name.
// (You may add additional conditions for date/time filtering if required.)
$searchQuery = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchQuery = trim($_GET['search']);
    $sql = "SELECT * FROM movies WHERE movie_name LIKE :search";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':search' => "%" . $searchQuery . "%"]);
} else {
    $sql = "SELECT * FROM movies";
    $stmt = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DIU Movie Ticket Management - Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Reset and typography */
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
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        .nav-links a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        /* Hero Section */
        .hero {
            background: #e8f0fe;
            padding: 80px 40px;
            text-align: center;
        }
        .hero h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
        }
        /* Search Form */
        .search-container {
            max-width: 600px;
            margin: 30px auto;
            text-align: center;
        }
        .search-container input[type="text"] {
            width: 80%;
            padding: 10px;
            border: 2px solid #3a7bd5;
            border-radius: 5px;
            font-size: 16px;
        }
        .search-container button {
            padding: 10px 20px;
            background: #3a7bd5;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-left: 10px;
        }
        .search-container button:hover {
            background: #3a6073;
        }
        /* Movie Grid Layout */
        .movie-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .movie-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 320px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }
        .movie-card:hover {
            transform: scale(1.03);
        }
        .movie-card img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
            flex: 1;
        }
        .card-body .movie-pic {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 0 auto 10px;
            display: block;
        }
        .card-body h3 {
            margin: 10px 0;
            font-size: 20px;
            text-align: center;
        }
        .card-body p {
            margin: 6px 0;
            color: #555;
            font-size: 14px;
            text-align: center;
        }
        .card-body .action-btn {
            display: block;
            width: 80%;
            margin: 15px auto 0;
            padding: 8px 12px;
            background: #3a7bd5;
            color: #fff;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        .card-body .action-btn:hover {
            background: #3a6073;
        }
        .card-body .action-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        /* Responsive adjustments */
        @media (max-width: 600px) {
            header {
                flex-direction: column;
            }
            .nav-links {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div>
            <h1>DIU Movie Ticket Management</h1>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="booking_details.php">My Bookings</a>
                <span>&#128100;</span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h2>Book your tickets with ease and enjoy the show!</h2>
    </section>

    <!-- Search Container -->
    <div class="search-container">
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Search Movies..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Movie Grid -->
    <section class="movie-grid">
        <?php while ($movie = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="movie-card">
                <div class="card-body">
                    <?php 
                    // Display the movie picture if exists; otherwise, display a placeholder image.
                    if (!empty($movie['movie_pic'])) {
                        echo '<img class="movie-pic" src="images/' . htmlspecialchars($movie['movie_pic']) . '" alt="' . htmlspecialchars($movie['movie_name']) . '">';
                    } else {
                        echo '<img class="movie-pic" src="images/placeholder.jpg" alt="No Image Available">';
                    }
                    ?>
                    <h3><?php echo htmlspecialchars($movie['movie_name']); ?></h3>
                    <p><strong>Seats Available:</strong> <?php echo $movie['seats']; ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($movie['show_date']); ?></p>
                    <p><strong>Time:</strong> <?php echo date("g:i A", strtotime($movie['show_time'])); ?></p>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($movie['seats'] > 0): ?>
                            <a class="action-btn" href="book_movie.php?id=<?php echo $movie['id']; ?>">Book Ticket</a>
                        <?php else: ?>
                            <button class="action-btn" disabled>Sold Out</button>
                        <?php endif; ?>
                    <?php else: ?>
                        <a class="action-btn" href="login.php">Login to Book</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </section>
</body>
</html>
