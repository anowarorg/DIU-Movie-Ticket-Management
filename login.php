<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user by email
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the entered password with the stored hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login | DIU Movie Ticket Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Global Styles for Login Page */
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
        .nav-links a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        /* Login Form Container */
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background: #3a7bd5;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #3a6073;
        }
        .form-container p {
            text-align: center;
            margin-top: 10px;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>DIU Movie Ticket Management</h1>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="signup.php">Sign Up</a>
        </div>
    </header>
    <!-- Login Form Container -->
    <div class="form-container">
        <h2>User Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Not registered? <a href="signup.php">Sign Up Here</a></p>
    </div>
</body>
</html>
