<?php
// admin/adminportal.php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // (For demo purposes, hard-coded credentials; in production, use database lookup)
    if ($username == 'admin' && $password == 'admin123') {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login | DIU Movie Ticket Management</title>
    <!-- Link to admin-specific CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
      /* Additional inline CSS for better UI – move this to admin/style.css if desired */
      body {
          background-color: #f0f2f5;
          font-family: Arial, sans-serif;
      }
      .login-container {
          width: 350px;
          padding: 30px;
          background: #ffffff;
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
      }
      .login-container h2 {
          text-align: center;
          margin-bottom: 20px;
      }
      .login-container input[type="text"],
      .login-container input[type="password"] {
          width: 100%;
          padding: 12px;
          margin: 8px 0;
          border: 1px solid #ccc;
          border-radius: 5px;
          box-sizing: border-box;
      }
      .login-container button {
          width: 100%;
          padding: 12px;
          background-color: #2980b9;
          color: #fff;
          border: none;
          border-radius: 5px;
          cursor: pointer;
      }
      .login-container button:hover {
          background-color: #1e6391;
      }
      .error {
          color: red;
          text-align: center;
      }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
