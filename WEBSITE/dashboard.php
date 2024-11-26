<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<?php
require 'includes/config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && (($_SESSION["services"] === "NS"))) {
  // Initialize an array to store the selected services
  $selected_services = [];

  // Loop through the checkboxes to find which ones are checked
  if (isset($_POST['service_codes'])) {
      foreach ($_POST['service_codes'] as $code) {
          $selected_services[] = $code;
      }

      // Create a string of the selected service codes
      $selected_services_string = implode(", ", $selected_services);

      // Output the result (or store it in a variable)
     // echo "Selected Services: " . htmlspecialchars($selected_services_string);
        $username = $_SESSION['username'];
        $updateSql = "UPDATE users SET services = ? WHERE username = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ss", $selected_services_string,$username);

        if ($stmt->execute()) {
            $message = "Services added sucessfully...";
            $message_type = 'success';
            //echo $message;
        } else {
            $message = "Error: " . $stmt->error;
            $message_type = 'error';
            //echo $message;
        }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/dashboardstyle.css">
</head>
<body>

<div class="navbar">
  <h2>Welcome to Dashboard !!</h2>
  <div class="dropdown">
    <button class="dropbtn">User: <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>&#11206</button>
    <div class="dropdown-content">
      <a href="logout.php"><img src="images/logout.png" height=15px width =15px></img> &nbsp&nbsp&nbsp&nbsp Logout </a><br>
      <a href="userprofile.php"><img src="images/userprofile.png" height=15px width =15px></img> &nbsp&nbsp&nbsp&nbsp User Profile </a>
    </div>
  </div>
</div>

<div class="sidebar">
  <a href="dashboard_settings.php">Settings</a>
  <a href="api_vault.php">API Vault</a>
  <div class="service-dropdown">
    <div class="service-dropbtn">&nbsp&nbsp&nbspServices&#11206</div>
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
  <a href="support.php">Support</a>
  <a href="contact.php">Contact Us</a>
</div>

<div style="margin-left:200px; padding:20px;" class="content">
  
  <!-- Your content goes here -->
  <h4> Your Services: </h4>
  <?php
        require 'includes/config.php';
        require 'includes/functions.php';

        $username = $_SESSION["username"];
        $sql2 = "SELECT id, services FROM users WHERE username='$username'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $services = $row2['services'];
        $_SESSION["services"] = $services;
        //echo $services;
    ?>
     <?php if ($services == "NS") : ?>
        <form method="post" action="" class="service-form active">
            <?php
            // Define the service codes
            $service_codes = ["QR Code Generator", "Image OCR", "Speech to Text", "Text To Speech", "Voice Translation", "Text Translation", "Audio Chunking", "Image Object Recognition", "Face Verification", "Face Recognition"];
            
            // Loop to create checkboxes
            foreach ($service_codes as $code) {
                echo '<label><input type="checkbox" name="service_codes[]" value="' . htmlspecialchars($code) . '"> ' . htmlspecialchars($code) . '</label><br>';
            }
            ?>
            <input type="submit" value="Submit">
        </form>
    <?php else : ?>
        <?php $your_services = explode(", ", $services);
              $count = 0;
              foreach($your_services as $service){
                $count ++;
                echo $count;
                echo ")  ".$service;
                echo "<br>";
              }
        ?>

        <p>Change Services From Settings</p>
    <?php endif; ?>

    
</div>

<div class="footer" >
  <p>Footer content</p>
</div>

</body>
</html>
