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
    <a href="contact.php">Contact Us</a>
</div>

<div style="margin-left:200px; padding:20px;" class="content">
  
  <!-- Your content goes here -->
  <h4>User Profile Settings: </h4>
  <style>
    #newmail{
        display:none;
    }

  </style>
  <script>
    var display = 2;
    function showform(){
        if(display %2 == 0){
        document.getElementById('changemailbutton').value = 'Cancel';
        const newmailvar = document.getElementById('newmail');
        newmailvar.style.display = 'block';
        //newmailvar.querySelector('input').required = true;
        const newmaillabel = document.getElementById('newmaillabel');
        newmaillabel.style.display = 'block';
        display++;
        }
        else{
        document.getElementById('changemailbutton').value = 'Change Mail';
        const newmailvar = document.getElementById('newmail');
        newmailvar.style.display = 'none';
        //newmailvar.querySelector('input').required = false;
        const newmaillabel = document.getElementById('newmaillabel');
        newmaillabel.style.display = 'none';
        display++;

        }

    }
    function chngpwd(){

      const oldpwdvar = document.getElementById('oldpwd');
      if(oldpwdvar.value == <?php ?>){
        
      }

    }
    </script>
  <form id='profile_form' method='post' action>
    <label for='username'>Username: </label>
    <input type = 'text' id="username" name="username" placeholder=<?php  $username = $_SESSION['username'];echo $username;?> disabled ><br>
    <label for='email'> Email: </label>
    <input type="email" id="email" name="email" placeholder=<?php  $email = $_SESSION['email']; echo $email; ?> disabled>
    <input type = 'button' id ='changemailbutton' name='change email' value='Change Email' onclick='showform()'><br>
    <label for='newmail' id = 'newmaillabel' style="display:none;">Enter new mail:  <input type = 'email' id='newmail' name='newmail' >
    </label>
    <label> Change Password: </label>
    <label> old Password: </lable>
    <input type = 'password' id='oldpwd' name='oldpwd'>
    <label> New Password: </label>
    <input type = 'password' id='newpwd' name='newpwd'>
    <label> Confirm Password:</label>
    <input type = 'password' id='cnfpwd' name='cnfpwd'>
    <input type = 'button' name = 'changepwd' id='changepwd' value='Change Password' onclick = 'chngpwd()'>

    



    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</form>

<div class="footer" >
  <p>Footer content</p>
</div>

</body>
</html>
