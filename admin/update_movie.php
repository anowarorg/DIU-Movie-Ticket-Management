<?php
// admin/update_movie.php
session_start();

// Check if the admin is logged in; otherwise, redirect to the admin login page.
if (!isset($_SESSION['admin'])) {
    header("Location: adminportal.php");
    exit();
}

include '../connection.php';

/**
 * Function to resize an image to the desired dimensions.
 * Requires the GD library.
 *
 * @param string $srcPath   The source file path.
 * @param string $destPath  The destination file path.
 * @param int    $desiredWidth  Desired width (default 120).
 * @param int    $desiredHeight Desired height (default 120).
 *
 * @return bool  True on success, false otherwise.
 */
function resizeImage($srcPath, $destPath, $desiredWidth = 120, $desiredHeight = 120) {
    // Get current dimensions and image type
    list($width, $height, $imageType) = getimagesize($srcPath);
    
    // Create image resource from source based on its type
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $srcImage = imagecreatefromjpeg($srcPath);
            break;
        case IMAGETYPE_PNG:
            $srcImage = imagecreatefrompng($srcPath);
            break;
        case IMAGETYPE_GIF:
            $srcImage = imagecreatefromgif($srcPath);
            break;
        default:
            return false; // Unsupported image type
    }
    
    // Create a new blank image with desired dimensions
    $dstImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
    
    // Preserve transparency for PNG and GIF
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagecolortransparent($dstImage, imagecolorallocatealpha($dstImage, 0, 0, 0, 127));
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
    }
    
    // Resize the image
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, 
                       $desiredWidth, $desiredHeight, $width, $height);
    
    // Save the resized image based on type
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($dstImage, $destPath, 90); // Quality: 90%
            break;
        case IMAGETYPE_PNG:
            imagepng($dstImage, $destPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($dstImage, $destPath);
            break;
    }
    
    // Free up memory
    imagedestroy($srcImage);
    imagedestroy($dstImage);
    
    return true;
}

// Check if a movie ID is provided via GET.
if (isset($_GET['id'])) {
    $movie_id = $_GET['id'];

    // Retrieve the movie record from the database.
    $sql = "SELECT * FROM movies WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $movie_id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the movie does not exist, display an error.
    if (!$movie) {
        die("Movie not found.");
    }
} else {
    // If no id was provided, display an error.
    die("No movie ID provided.");
}

// Process the form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data.
    $movie_name = trim($_POST['movie_name']);
    $seats      = intval($_POST['seats']);  // New total capacity as entered by admin.
    $show_date  = $_POST['show_date'];
    $show_time  = $_POST['show_time'];
    
    // Get the number of seats already booked for this movie.
    $sqlBooked = "SELECT SUM(num_tickets) as booked FROM bookings WHERE movie_id = :movie_id";
    $stmtBooked = $conn->prepare($sqlBooked);
    $stmtBooked->execute([':movie_id' => $movie_id]);
    $bookedData = $stmtBooked->fetch(PDO::FETCH_ASSOC);
    $bookedSeats = isset($bookedData['booked']) ? intval($bookedData['booked']) : 0;
    
    // Calculate the updated available seats (new total capacity minus booked seats).
    $updated_available = $seats - $bookedSeats;
    if ($updated_available < 0) {
        $updated_available = 0;
    }
    
    // Process picture upload if a new file is provided.
    $movie_pic = $movie['movie_pic']; // default: keep existing picture
    if (isset($_FILES['movie_pic']) && $_FILES['movie_pic']['error'] == UPLOAD_ERR_OK) {
        // Get file info
        $fileTmpPath   = $_FILES['movie_pic']['tmp_name'];
        $fileName      = $_FILES['movie_pic']['name'];
        $fileSize      = $_FILES['movie_pic']['size'];
        $fileType      = $_FILES['movie_pic']['type'];
        $fileNameCmps  = pathinfo($fileName);
        $fileExtension = strtolower($fileNameCmps['extension']);
        
        // Allowed file extensions
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Sanitize and generate a new filename to avoid collisions
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            
            // Directory where uploaded files will be saved
            $uploadFileDir = '../images/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            $dest_path = $uploadFileDir . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Resize the image to 120px x 120px
                $resizedDest = $uploadFileDir . "resized_" . $newFileName;
                if (resizeImage($dest_path, $resizedDest, 120, 120)) {
                    // Optionally delete the original file
                    unlink($dest_path);
                    $movie_pic = basename($resizedDest);
                } else {
                    $error = "There was an error resizing the uploaded image.";
                }
            } else {
                $error = "There was an error moving the uploaded file.";
            }
        } else {
            $error = "Upload failed. Allowed file types: " . implode(', ', $allowedfileExtensions);
        }
    }
    
    // If no errors encountered, update the movie record
    if (!isset($error)) {
        $sql = "UPDATE movies 
                SET movie_name = :movie_name, seats = :seats, show_date = :show_date, show_time = :show_time, movie_pic = :movie_pic
                WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':movie_name' => $movie_name,
            ':seats'      => $updated_available,
            ':show_date'  => $show_date,
            ':show_time'  => $show_time,
            ':movie_pic'  => $movie_pic,
            ':id'         => $movie_id
        ]);
        
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update Movie | DIU Movie Ticket Management</title>
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
        /* Header Styling */
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
        .header-right a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        /* Form Container Styling */
        .form-container {
            max-width: 450px;
            margin: 50px auto;
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
            margin-top: 15px;
            font-size: 16px;
        }
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="date"],
        .form-container input[type="time"],
        .form-container input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .form-container .picture-hint {
            font-size: 14px;
            color: #666;
            text-align: left;
            margin-bottom: 5px;
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
        /* Back to Dashboard Link */
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #3a7bd5;
            text-decoration: none;
            font-size: 16px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        /* Error message styling */
        .error {
            color: red;
            text-align: center;
            font-size: 16px;
        }
        /* Display the existing picture */
        .current-pic {
            margin: 10px 0;
            text-align: center;
        }
        .current-pic img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h2>Update Movie</h2>
        <div class="header-right">
            <a href="dashboard.php">Back to Dashboard</a> |
            <a href="../logout.php">Logout</a>
        </div>
    </header>
    
    <!-- Form Container for Updating Movie -->
    <div class="form-container">
        <h2>Update Movie</h2>
        <?php 
        // Display error if set
        if (isset($error)) { 
            echo "<p class='error'>$error</p>"; 
        }
        ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="movie_name">Movie Name:</label>
            <input type="text" id="movie_name" name="movie_name" required value="<?php echo htmlspecialchars($movie['movie_name']); ?>">
            
            <label for="seats">Total Seats (Capacity):</label>
            <input type="number" id="seats" name="seats" required value="<?php echo htmlspecialchars($movie['seats']); ?>">
            
            <label for="show_date">Date of Show:</label>
            <input type="date" id="show_date" name="show_date" required value="<?php echo htmlspecialchars($movie['show_date']); ?>">
            
            <label for="show_time">Time of Show:</label>
            <input type="time" id="show_time" name="show_time" required value="<?php echo htmlspecialchars($movie['show_time']); ?>">
            
            <label for="movie_pic">Movie Picture:</label>
            <?php if (!empty($movie['movie_pic'])): ?>
                <div class="current-pic">
                    <p>Current Picture:</p>
                    <img src="../images/<?php echo htmlspecialchars($movie['movie_pic']); ?>" alt="<?php echo htmlspecialchars($movie['movie_name']); ?>">
                </div>
            <?php endif; ?>
            <div class="picture-hint">Picture will be resized to 120px x 120px (Upload new to replace)</div>
            <input type="file" id="movie_pic" name="movie_pic" accept=".jpg, .jpeg, .png, .gif">
            
            <button type="submit">Update Movie</button>
        </form>
    </div>
</body>
</html>
