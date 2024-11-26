<?php
session_start();
require 'includes/config.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Function to generate a random API key
function generateAPIKey() {
    return bin2hex(random_bytes(25)); // Generates a 50-character hexadecimal string
}

// Function to check if the API key exists in the database
function isAPIKeyExists($apiKey, $conn) {
    $query = "SELECT COUNT(*) AS count FROM users WHERE api = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $apiKey);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

// Check if the "Generate API Key" button is clicked
if (isset($_POST['generate_api_key'])) {
    // Generate a new API key
    $newAPIKey = generateAPIKey();

    // Check if the API key already exists in the database
    if (!isAPIKeyExists($newAPIKey, $conn)) {
        // Store the API key in the database
        $username = $_SESSION['username'];
        $query = "UPDATE users SET api = ? WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $newAPIKey, $username);
        $stmt->execute();

        $_SESSION["api"] = $newAPIKey;
    }
}
$showDiv = false;
if (isset($_POST['Show_Key'])) {
    $showDiv = true;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>API VAULT</title>
  <link rel="stylesheet" href="css/dashboardstyle.css">
  <style>
    #showkey {
      display: <?php echo $showDiv ? 'block' : 'none'; ?>; /* Show or hide the div based on PHP flag */
      padding: 20px;
      margin-top: 20px;
      border:2px solid red;
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
      <a href="logout.php"><img src="images/logout.png" height=15px width =15px></img> &nbsp&nbsp&nbsp&nbsp Logout </a><br>
      <a href="userprofile.php"><img src="images/userprofile.png" height=15px width =15px></img> &nbsp&nbsp&nbsp&nbsp User Profile </a>
    </div>
  </div>
</div>

<div class="sidebar">
  <a href="dashboard.php">Dashboard</a>
  <a href="dashboard_settings.php">Settings</a>
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
  <h4> API VAULT: </h4>
  <?php
        require 'includes/config.php';
        require 'includes/functions.php';

        $username = $_SESSION["username"];
        $sql2 = "SELECT id, api FROM users WHERE username='$username'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $api_key = $row2['api'];
        $_SESSION["api"] = $api_key;
    ?>
    <?php if (!$api_key) : ?>
        <form method="post" action="" class="service-form active">
            <button type="submit" name="generate_api_key">Generate API Key</button>
        </form>
    <?php else : ?>
        <p>Click button to view api key:</p>
        <form method='post' action="">
            <button type="submit" name = "Show_Key"> Show Key </button>
        </form>
        <div id="showkey">
            API KEY:
            <?php echo $api_key; ?>
            ( <span id="timer">10</span> sec... )<br>
            
        </div>
    <?php endif; ?>
    
  <?php if ($showDiv): ?>
    <script>
      var x = document.getElementById("showkey");
      var timerSpan = document.getElementById("timer");
      var timeLeft = 10;

      x.style.display = "block";

      var countdown = setInterval(function() {
        timeLeft--;
        timerSpan.textContent = timeLeft;
        if (timeLeft <= 0) {
          clearInterval(countdown);
          x.style.display = "none";
        }
      }, 1000);

      setTimeout(function() {
        x.style.display = "none";
      }, 10000); // Hide after 10 seconds
    </script>
  <?php endif; ?>
       
</div>

<div class="footer" >
  <p>Footer content</p>
</div>

</body>
</html>
