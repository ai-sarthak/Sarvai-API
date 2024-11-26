<?php
require 'includes/config.php';
require 'includes/functions.php';

$message = '';
$message_type = '';
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST["email"]);
    $password = sanitizeInput($_POST["password"]);

    $sql = "SELECT id, password FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    $sql2 = "SELECT id, username FROM users WHERE email='$email'";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $username = $row2['username'];
    
    

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $message = "login sucess...";
            $message_type = 'success';
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["email"] = $email;
            $_SESSION["username"] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Invalid Password";
            $message_type = 'error';
        }
    } else {
            $message = "No User Found Please Signup First... ";
            $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .notification {
            display: none;
            padding: 1rem;
            margin-top: 1rem;
            text-align: center;
            border-radius: 5px;
        }
        .notification.success {
            background-color: #4CAF50;
            color: white;
        }
        .notification.error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Login</h1>
        <?php if ($message): ?>
            <div id="notification" class="notification <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', (event) => {
                    const notification = document.getElementById('notification');
                    notification.style.display = 'block';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 10000); // 10 seconds
                });
            </script>
        <?php endif; ?>
        <form method='POST' action='' id='loginForm'>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Our Company</p>
    </footer>
    
</body>
</html>


