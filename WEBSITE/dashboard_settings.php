<?php
session_start();
require 'includes/config.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<script>
        // Inline JavaScript function to show confirmation popup
        
        function confirmAction() {
            var result = confirm("Are you sure you want to perform this action?");
            document.getElementById("confirmResult").value = result ? "1" : "0";
            document.getElementById("deleteuserform").submit();
        }
        
        function showserviceform(){
            

        }
</script>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="css/dashboard_settings_style.css">

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

<div class="sidebar" >
    <br><br><br>
  <a href="dashboard.php">Dashboard</a>
  <a href="api_vault.php">API Vault</a>
  <div class="service-dropdown" style="padding: 10px 15px;">
    <div class="service-dropbtn" >&nbsp&nbsp&nbspServices&#11206</div>
    <div class="service-dropdown-content" >
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

<div  class = "content">
  
  <!-- Your content goes here -->
  <h4> Settings: </h4>
  <hr>
  <div id = "change_service" style="padding-bottom: 20px;">
        <p> Change Services: </p>
        <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to store the selected services
    $username = $_SESSION['username'];
    $sql2 = "SELECT services FROM users WHERE username='$username'";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $selected_services = array_map('trim', explode(",", $row2['services'])); // Trim each service after exploding
    
    // Loop through the checkboxes to find which ones are checked
    if (isset($_POST['service_codes'])) {
        if (isset($_POST['addservice'])) {
            $service_array = $_POST['service_codes'];
            foreach ($service_array as $code) {
                if (!in_array(trim($code), $selected_services)) { // Trim the code to remove any leading/trailing spaces
                    $selected_services[] = trim($code); // Trim the code before adding to the array
                }
            }

            // Create a string of the selected service codes
            $selected_services_string = implode(", ", $selected_services); // No space after the comma

            // Output the result (or store it in a variable)
            $username = $_SESSION['username'];
            $updateSql = "UPDATE users SET services = ? WHERE username = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("ss", $selected_services_string, $username);

            if ($stmt->execute()) {
                $message = "Services added successfully...";
                $message_type = 'success';
                echo $message;
            } else {
                $message = "Error: " . $stmt->error;
                $message_type = 'error';
                echo $message;
            }
        
            }elseif(isset($_POST['deleteservice'])){
                foreach ($_POST['service_codes'] as $code) {
                    if (in_array($code, $selected_services, true)) {
                        $selected_services = array_diff($selected_services, [$code]);
                    }
                        
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
                      $message = "Services Deleted sucessfully...";
                      $message_type = 'success';
                      echo $message;
                  } else {
                      $message = "Error: " . $stmt->error;
                      $message_type = 'error';
                      echo $message;
                  }

            }
          
                
        }
    }
          ?>
        <form method="post" action="" class="service-form active">
            <?php
            // Define the service codes
            $service_codes = ["QR Code Generator", "Image OCR", "Speech to Text", "Text To Speech", "Voice Translation", "Text Translation", "Audio Chunking", "Image Object Recognition", "Face Verification", "Face Recognition"];
            
            // Loop to create checkboxes

            foreach ($service_codes as $code) {
                echo '<label><input type="checkbox" name="service_codes[]" value="' . htmlspecialchars($code) . '"> ' . htmlspecialchars($code) . '</label><br>';
            }
            ?>
            <br>
            <input type="submit" name ="addservice"value="Add More Services">
            <input type="submit" name ="deleteservice"value="Delete Selected Services">
            </form>
            <br><br><br>
        
        
            
  </div>
  <hr>
  <div id = "change_theme" style="padding-bottom: 20px;">
        <p> Change Theme: </p>
        <form>
        <input type = 'radio' name='ct'> Dark 
        <input type = 'radio' name='ct'> Light
        </form> 
  </div>
  <hr>
  <div id = "remove_acc" style="margin-bottom: 20px;background-color: red;color:#ddd ;display:flex; width:100%;">
        <p style="color:yellow;">&#x26A0</p><p style="color:white;"> Delete My Account and all Services</p><br>
       <form id='deleteuserform' method='post' action='deleteuser.php'> 
                <input type="hidden" id="confirmResult" name="confirmResult">
                <button type="submit" style="background-color:yellow;border-radius:45px;height: 50px;width:150px;" onclick='confirmAction()' > Delete My Account and all Services </button>
       </form>
    </div>
  <hr>

  

    
</div>

<div class="footer">
  <p>Footer content</p>
</div>

</body>
</html>