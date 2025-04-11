<?php
// signup.php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name             = $_POST['name'];
    $email            = $_POST['email'];
    $phone            = $_POST['phone'];
    $address          = $_POST['address'];
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if the email is already registered
        $sqlCheck = "SELECT * FROM users WHERE email = :email";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->execute([':email' => $email]);
        if ($stmtCheck->rowCount() > 0) {
            $error = "This email is already registered!";
        } else {
            // Hash the password and insert new user record
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (name, email, phone, address, password)
                    VALUES (:name, :email, :phone, :address, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':phone'    => $phone,
                ':address'  => $address,
                ':password' => $hashed_password
            ]);
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign Up | DIU Movie Ticket Management</title>
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
        /* Header styling (same as index.php) */
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
        /* Signup Form Container */
        .form-container {
            max-width: 450px;
            margin: 50px auto;
            padding: 30px;
            background: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
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
        .form-container p {
            text-align: center;
            margin-top: 15px;
        }
        .form-container p a {
            color: #3a7bd5;
            text-decoration: none;
        }
        .form-container p a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
        }
        /* Navigation link on top */
        .nav-top {
            text-align: right;
            margin: 10px 20px;
        }
        .nav-top a {
            text-decoration: none;
            color: #2980b9;
            font-size: 16px;
        }
    </style>
</head>
<body>
    
    <!-- Header Section -->
    <header>
        <h1>DIU Movie Ticket Management</h1>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </div>
    </header>
    <!-- Signup Form Container -->
    <div class="form-container">
        <h2>User Sign Up</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already registered? <a href="login.php">Login Here</a></p>
    </div>
</body>
</html>
