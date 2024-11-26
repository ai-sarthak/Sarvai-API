<?php
session_start();
require 'includes/config.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support</title>
  <link rel="stylesheet" href="css/dashboardstyle.css">
  <style>
    #com_info{
        font-size:20px;
        color:Black;

    }

</style>
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
</div>

<div style="margin-left:200px; padding:20px;" class="content">
  
  <!-- Your content goes here -->
  <h4>Contact Us: </h4>
  <div id = com_info>
  Please Contact us Between Working Hours only.<br><br> 10:00 AM(IST) to 05:00 PM(IST) <br><br>
     Monday to Friday. <br><br>

  Contact No. : +91 xxxxx xxxxx <br><br>
  Tel. No. : 020 xxxxx xxxxx <br><br>
  email: contactus@sarvai.in <br><br>
  Please Feel free to use our Customer Support For Technical Assistance.
</div>

  

</div>

<div class="footer" >
  <p>Footer content</p>
</div>

</body>
</html>
