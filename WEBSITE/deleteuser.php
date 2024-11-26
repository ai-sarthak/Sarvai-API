
<!doctype html>
<?php 
    require 'includes/config.php';
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $confirmResult = $_POST['confirmResult'];
        if ($confirmResult == "1") {
            echo "Action confirmed!";
            $username = $_SESSION["username"]; 
            $Sql = "DELETE FROM users WHERE username=?";
            $stmt = $conn->prepare($Sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            header("Location: signup.php");
            exit();
        } else {
            
            header("Location: dashboard_settings.php");
        }
    }
    
?>


