<?php
// admin/add_movie.php
session_start();

// Restrict access to admin users only
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
    
    // Create a source image resource based on image type
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
    
    // Create a new true color image with desired dimensions
    $dstImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
    
    // Preserve transparency for PNG and GIF
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagecolortransparent($dstImage, imagecolorallocatealpha($dstImage, 0, 0, 0, 127));
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
    }
    
    // Resize: Copy and resize the image from source to destination
    imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, 
                       $desiredWidth, $desiredHeight, $width, $height);
    
    // Save the resized image to destination path based on image type
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $movie_name = trim($_POST['movie_name']);
    $seats      = intval($_POST['seats']);
    $show_date  = $_POST['show_date'];
    $show_time  = $_POST['show_time'];
    
    // Handle movie picture upload
    $movie_pic = null; // default value
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
            // Sanitize and generate new filename
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            
            // Directory to save the uploaded files (make sure this folder exists and is writable)
            $uploadFileDir = '../images/';
            $dest_path = $uploadFileDir . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Define destination for resized image
                $resizedDest = $uploadFileDir . "resized_" . $newFileName;
                // Resize the image to 120px x 120px
                if (resizeImage($dest_path, $resizedDest, 120, 120)) {
                    // Optionally delete the original file if only the resized version is needed.
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
    
    // Proceed if no errors encountered during file upload processing
    if (!isset($error)) {
        // SQL to insert a new movie record including movie_pic field
        $sql = "INSERT INTO movies (movie_name, seats, show_date, show_time, movie_pic) 
                VALUES (:movie_name, :seats, :show_date, :show_time, :movie_pic)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':movie_name' => $movie_name,
            ':seats'      => $seats,
            ':show_date'  => $show_date,
            ':show_time'  => $show_time,
            ':movie_pic'  => $movie_pic
        ]);
        
        // Redirect to the admin dashboard after successful insertion
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add New Movie | DIU Movie Ticket Management</title>
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
        /* Header styling (similar to index.php) */
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
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>DIU Movie Ticket Management</h1>
        <div class="header-right">
            <a href="dashboard.php">Back to Dashboard</a> |
            <a href="../logout.php">Logout</a>
        </div>
    </header>
    
    <!-- Form Container for Adding New Movie -->
    <div class="form-container">
        <h2>Add New Movie</h2>
        <?php 
            if(isset($error)) { 
                echo "<p class='error'>$error</p>"; 
            }
        ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="movie_name">Movie Name:</label>
            <input type="text" id="movie_name" name="movie_name" required>
            
            <label for="seats">Number of Seats:</label>
            <input type="number" id="seats" name="seats" required>
            
            <label for="show_date">Date of Show:</label>
            <input type="date" id="show_date" name="show_date" required>
            
            <label for="show_time">Time of Show:</label>
            <input type="time" id="show_time" name="show_time" required>
            
            <label for="movie_pic">Movie Picture:</label>
            <div class="picture-hint">Picture will be resized to 120px x 120px</div>
            <input type="file" id="movie_pic" name="movie_pic" accept=".jpg, .jpeg, .png, .gif" required>
            
            <button type="submit">Add Movie</button>
        </form>
    </div>
</body>
</html>
