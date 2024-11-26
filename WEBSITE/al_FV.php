<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require 'includes/config.php';
//06808b22c78f43483e0f04e8ea8b06c43820e3d3486bd10496
//"Audio Chunking"
$message = " ";
$valid_service = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['validate'])) {
        $user_api_key = $_POST['user_api_key'];
        $sql2 = "SELECT services FROM users WHERE API='$user_api_key'";
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $services = $row2['services'];
            $services_array = explode(", ", $services);

            if (in_array("Face Verification", $services_array)) {
                $message = "Successfully Validated. You can use the service as per your API tier.";
                $valid_service = true;
            } else {
                $message = "You have not added this service. Please add this service to use.";
                $valid_service = false;
            }
        } else {
            $message = "Invalid API Key.";
            $valid_service = false;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Face Verification</title>
  <link rel="stylesheet" href="css/dashboardstyle.css">
  <style>
    #show {
      display: none; /* currently hidden */
      padding: 20px;
      margin-top: 20px;
      width: fit-content;
    }

    #hide {
      display: block; /* Show or hide the div based on PHP flag */
      padding: 20px;
      margin-top: 20px;
      border: 2px solid red;
      width: fit-content;
    }
  </style>
</head>
<body>

<div class="navbar">
  <h2>Welcome to Dashboard !!</h2>
  <div class="dropdown">
    <button class="dropbtn">User: <span><?php echo htmlspecialchars($_SESSION["username"]); ?></span>&#11206</button>
    <div class="dropdown-content">
      <a href="logout.php"><img src="images/logout.png" height=15px width=15px></img> &nbsp&nbsp&nbsp&nbsp Logout </a><br>
      <a href="userprofile.php"><img src="images/userprofile.png" height=15px width=15px></img> &nbsp&nbsp&nbsp&nbsp User Profile </a>
    </div>
  </div>
</div>

<div class="sidebar">
<a href="dashboard.php">Dashboard</a>
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
  <h4>Face Verification</h4>
  <?php
        require 'includes/config.php';
        require 'includes/functions.php';
        $this_service = "Face Verification";
        $username = $_SESSION["username"];
        $sql2 = "SELECT id, services FROM users WHERE username='$username'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $services = $row2['services'];
        $_SESSION["services"] = $services;
        $your_services = explode(", ", $services);
  ?>
    <div id="hide">
      <form method='post' action=''>
        <label>Enter Your API Key:</label>
        <input type='password' name='user_api_key'>
        <input type="hidden" name="this_service" value="<?php $this_service?>">
        <button type="submit" name='validate'>Validate</button>
      </form>
    </div>
    <div id="show">
        Let us know more about this service...
    </div>

    <?php echo $message."<br>"; ?>
    <?php if ($valid_service): ?>
        <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var x = document.getElementById("show");
            var y = document.getElementById("hide");

            x.style.display = "block";
            y.style.display = "none";
        });
        </script>
    <?php endif; ?>
</div>

<div class="footer">
  <p>Footer content</p>
</div>

</body>
</html>
