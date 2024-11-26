<?php
session_start();
require 'includes/config.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $name = sanitizeInput($_POST['name']);
    $issue = sanitizeInput($_POST['issue']);

    // Generate unique support ticket ID
    $support_ticket_id = uniqid('ticket_');

    // Append the support ticket details to the user's support field in the database
    $support_append = "Name: $name\nEmail: $email\nIssue: $issue\nSupport Ticket ID: $support_ticket_id\n";
    $insertSql = "UPDATE users SET support = ? WHERE username = ?";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("ss", $support_append, $username);
    $stmt->execute();
    $stmt->close();

    // Send confirmation email using PHPMailer
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
        $mail->setFrom('sartjossh4@gmail.com', 'Sarvai');
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Support Ticket Raised';
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
                        <img src='https://sarvai.000webhostapp.com/images/logo.png' alt='Your Company Logo' width='100'>
                    </div>
                    <div class='email-body'>
                        <h2>Support Ticket Confirmation</h2>
                        <p>Dear $name,</p>
                        <p>Thank you for contacting support. Your issue has been recorded and we will get back to you shortly. Below are the details of your support ticket:</p>
                        <p><strong>Support Ticket ID:</strong> $support_ticket_id</p>
                        <p><strong>Issue:</strong> $issue</p>
                    </div>
                    <div class='email-footer'>
                        <p>&copy; 2024 Sarvai. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        $mail->send();
        $message = "Successfully submitted. Please check your email for the support ticket confirmation.";
    } catch (Exception $e) {
        $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support</title>
    <link rel="stylesheet" href="css/dashboardstyle.css">
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
    <div class="navbar">
        <h2>Welcome to Dashboard !!</h2>
        <div class="dropdown">
            <button class="dropbtn">User: <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>&#11206</button>
            <div class="dropdown-content">
                <a href="logout.php"><img src="images/logout.png" height=15px width=15px> &nbsp;&nbsp;&nbsp; Logout</a><br>
                <a href="userprofile.php"><img src="images/userprofile.png" height=15px width=15px> &nbsp;&nbsp;&nbsp; User Profile</a>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <a href="dashboard.php">Dashboard</a>
        <a href="dashboard_settings.php">Settings</a>
        <a href="api_vault.php">API Vault</a>
        <div class="service-dropdown">
            <div class="service-dropbtn">&nbsp;&nbsp;&nbsp;Services&#11206</div>
            <div class="service-dropdown-content">
                <a href="al_QCG.php">QR Code Generator</a>
                <a href="al_IOCR.php">Image OCR</a>
                <a href="al_S2T.php">Speech to Text</a>
                <a href="al_T2S.php">Text To Speech</a>
                <a href="al_VT.php">Voice Translation</a>
                <a href="al_TT.php">Text Translation</a>
                <a href="al_AC.php">Audio Chunking</a>
                <a href="al_ICOR.php">Image Object Recognition</a>
                <a href="al_FV.php">Face Verification</a>
                <a href="al_FR.php">Face Recognition</a>
            </div>
        </div>
        <a href="contact.php">Contact Us</a>
    </div>

    <div style="margin-left:200px; padding:20px;" class="content">
        <h4>Technical Support</h4>
        <?php if ($message): ?>
            <div id="notification" class="notification <?php echo ($message_type === 'success') ? 'success' : 'error'; ?>">
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

        <form method="post" action="">
            <label for="name">Name:</label><br>
            <?php  $username = $_SESSION['username']; ?>
            <input type="text" id="name" name="name" placeholder="<?php echo $username;?>" value='<?php echo $username;?>' required><br><br>

            <label for="email">Email:</label><br>
            <?php $email = $_SESSION['email']; ?>
            <input type="email" id="email" name="email" placeholder="<?php echo $email; ?>" value="<?php echo $email; ?>" disabled><br><br>

            <label for="issue">Issue:</label><br>
            <textarea id="issue" name="issue" rows="4" cols="50" required></textarea><br><br>

            <button type="submit" name="submit">Submit</button><br>
        </form>
    </div>

    <div class="footer">
        <p>Footer content</p>
    </div>
</body>
</html>
