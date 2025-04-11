<?php
// admin/registered_users.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: adminportal.php");
    exit();
}
include '../connection.php';

// Fetch all registered users from the users table.
$sqlUsers = "SELECT * FROM users ORDER BY id ASC";
$stmtUsers = $conn->query($sqlUsers);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registered Users | DIU Movie Ticket Management</title>
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
        /* Header Styling (Consistent with index.php) */
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
        /* Content Container for the Table */
        .content-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h3 {
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
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background: #e9e9e9;
        }
        /* Back to Dashboard Button */
        .back-link {
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
        .back-link:hover {
            background: #3a6073;
        }
        /* Responsive adjustments */
        @media (max-width: 600px) {
            header {
                flex-direction: column;
                text-align: center;
            }
            .nav-links {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>DIU Movie Ticket Management</h1>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a> |
            <a href="registered_users.php">Registered Users</a> |
            <a href="../logout.php">Logout</a>
        </div>
    </header>
    
    <!-- Content Container -->
    <div class="content-container">
        <h3>Registered Users</h3>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 1;  // Initialize sequential counter starting at 1.
                while ($user = $stmtUsers->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td><?php echo htmlspecialchars($user['address']); ?></td>
                    <td>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <a class="back-link" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
