<?php
require 'includes/config.php';
require 'includes/functions.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {
        $username = sanitizeInput($_POST["username"]);
        $email = sanitizeInput($_POST["email"]);
        $password = password_hash(sanitizeInput($_POST["password"]), PASSWORD_BCRYPT);

        // Check if the username or email already exists
        $checkSql = "SELECT id FROM users WHERE email = ? OR username = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username or Email is already taken. Please choose another.";
            $message_type = 'error';
        } else {
            // Generate a unique 6-digit verification code
            $verification_code = mt_rand(100000, 999999);

            // Send verification email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'sartjosh4@gmail.com'; // SMTP username
                $mail->Password = 'fpti tkcq ltss cfkj'; // SMTP app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                $mail->Port = 587; // TCP port to connect to

                //Recipients
                $mail->setFrom('sartjosh4@gmail.com', 'Sarvai');
                $mail->addAddress($email, $username);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Verify your email';
                $mail->Body = "
                    <html>
                    <head>
                        <style>
                            .email-container {
                                font-family: Arial, sans-serif;
                                color: #333;
                                text-align: center;
                            }
                            .email-header {
                                background-color: #4CAF50;
                                padding: 10px;
                                color: white;
                            }
                            .email-body {
                                margin: 20px;
                                padding: 20px;
                                border: 1px solid #ddd;
                                border-radius: 10px;
                            }
                            .email-footer {
                                margin-top: 20px;
                                font-size: 12px;
                                color: #999;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='email-container'>
                            <div class='email-header'>
                                <h1>Sarvai</h1>
                                <img src='https://sarvai.000webhostapp.com/images/logo.png' alt='Sarvai Logo' width='100'>
                            </div>
                            <div class='email-body'>
                                <h2>Email Verification</h2>
                                <p>Dear $username,</p>
                                <p>Thank you for signing up. Please use the following verification code to complete your registration:</p>
                                <h3>$verification_code</h3>
                            </div>
                            <div class='email-footer'>
                                <p>&copy; 2024 Sarvai. All rights reserved.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                ";

                $mail->send();

                // Store verification code in session
                session_start();
                $_SESSION['verification_code'] = $verification_code;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;

                $message = "A verification code has been sent to your email.";
                $message_type = 'success';
            } catch (Exception $e) {
                $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                $message_type = 'error';
            }
        }
        $stmt->close();
    } elseif (isset($_POST["verification_code"])) {
        session_start();
        $verification_code = $_POST["verification_code"];

        if ($verification_code == $_SESSION['verification_code']) {
            $username = $_SESSION['username'];
            $email = $_SESSION['email'];
            $password = $_SESSION['password'];

            // Insert the user into the database
            $insertSql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("sss", $username, $email, $password);

            if ($stmt->execute()) {
                $message = "Signup successful, please login to continue...";
                $message_type = 'success';
                unset($_SESSION['verification_code']);
                unset($_SESSION['username']);
                unset($_SESSION['email']);
                unset($_SESSION['password']);
            } else {
                $message = "Error: " . $stmt->error;
                $message_type = 'error';
            }
            $stmt->close();
        } else {
            $message = "Verification code is incorrect.";
            $message_type = 'error';
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        <h1>Sign Up</h1>
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

        <?php if (!isset($_SESSION['verification_code'])): ?>
            <form method='POST' action='' id="signupForm">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Sign Up</button>
            </form>
        <?php else: ?>
            <form method='POST' action='' id="verificationForm">
                <label for="verification_code">Verification Code:</label>
                <input type="text" id="verification_code" name="verification_code" required>
                <button type="submit">Verify</button>
            </form>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2024 Your Company</p>
    </footer>
    <script src="js/scripts.js"></script>
</body>
</html>
