<?php
session_start();

$conn = mysqli_connect(
  '',
  '',
  '',
  '');

error_reporting(E_ERROR | E_PARSE);

if (empty($_SESSION['rrm_alias']) || empty($_SESSION['rrm_username'])) {
    header("location: /rrm/login");
}

if (isset($_POST['save_changes'])) {
    $email = $_POST['email'];
    $language = $_POST['language'];
    $alias = $_POST['alias'];

    $updateUser = "UPDATE rrm_users SET email = ?, language = ?, alias = ? WHERE username = ?";

    if ($updateStmt = mysqli_prepare($conn, $updateUser)) {
        mysqli_stmt_bind_param($updateStmt, "ssss", $email, $language, $alias, $_SESSION['rrm_username']);
        if (mysqli_stmt_execute($updateStmt)) {
            $error = "Successfully updated";
            $_SESSION['rrm_alias'] = $alias;
        } else {
            $error = "Network error";
        }
        mysqli_stmt_close($updateStmt);
    }
}

# === USER INFO (START)
$_EMAIL = "";
$_LANGUAGE = "";
# == USER INFO (END)

$getUser = "SELECT * FROM rrm_users WHERE username = '".$_SESSION['rrm_username']."' LIMIT 1";
if ($gotUser = mysqli_query($conn, $getUser)) {
    if ($row = mysqli_fetch_assoc($gotUser)) {
        $_EMAIL = $row['email'];
        $_LANGUAGE = $row['language'];
    }
}

# == LANG TYPES (START)
$lang_types = ['en'=>'English'];
# == LANG TYPES (END)
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings</title>
  <link rel="icon" href="../RRM.png" />
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100vw;
      height: 100vh;
    }

    .settings-container {
      width: 100%;
      max-width: 500px;
      margin: 20px;
    }
    
    .settings-section {
      background-color: #f9f9f9;
      padding: 20 20 10 20;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .settings-section h2 {
      margin-top: 0;
      margin-bottom: 15px;
      color: #333;
    }
    
    .settings-section select, 
    .settings-section input[type="text"], 
    .settings-section input[type="email"], 
    .settings-section button {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 4px;
      border: 1px solid #ccc;
      transition: 0.2s all;
      margin-top: 5px;
    }

    .settings-section select:focus, 
    .settings-section input[type="text"]:focus, 
    .settings-section input[type="email"]:focus {
        outline: 1px solid #7a2fbc;
    }
    
    .settings-section button {
      background-color: #6a1b9a;
      color: white;
      border: none;
      cursor: pointer;
      margin-top: 20px;
    }
    
    .settings-section button:hover {
      background-color: #8c3fa5;
    }
    
    .delete-btn {
      background-color: #d9534f;
      color: white;
    }
    
    @media screen and (max-width: 768px) {
      main.settings-container {
        padding: 0 10px;
      }
    
      .settings-section {
        margin: 0;
      }
    
      .settings-section select,
      .settings-section input[type="text"],
      .settings-section input[type="email"],
      .settings-section button {
        width: calc(100% - 20px);
        /* Adjustments for smaller screens */
      }
    }
  </style>
</head>
<body>
  <main class="settings-container">
    <div class="settings-section">
      <h2>Profile Details</h2>
      <form method="post" id="saveForm">
        <label for="alias">Alias</label>
        <input type="text" id="alias" name="alias" placeholder="Enter your alias" value="<?php echo $_SESSION['rrm_alias']; ?>" maxlength=30><br><br>
        
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo $_EMAIL; ?>" maxlength=240><br><br>
        
        <label for="language">Language Preference</label>
        <select id="language" name="language">
            <option value="<?php echo $_LANGUAGE; ?>"><?php echo $lang_types[$_LANGUAGE]; ?></option>
            <?php
            foreach ($lang_types as $key=>$index) {
                if ($key == $_LANGUAGE) {
                    continue;
                }
                echo '<option value="'.$key.'">'.$index.'</option>';
            }
            ?>
          </select>
        
        <button type="submit" name="save_changes">Save Changes</button>
        <div style="color: red; text-align: center; margin-top: 10px;"><?php echo $error; ?></div>
      </form>
    </div>
    
    <!--<div class="settings-section">-->
    <!--  <h2>Delete Account</h2>-->
    <!--  <p>Warning: This action cannot be undone!</p>-->
    <!--  <button class="delete-btn">Delete My Account</button>-->
    <!--</div>-->
  </main>
</body>
</html>
