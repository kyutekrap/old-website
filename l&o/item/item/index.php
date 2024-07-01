<?php
session_start();

$conn = mysqli_connect(
  '',
  '',
  '',
  '');

error_reporting(E_ERROR | E_PARSE);

if (empty($_SESSION['rrm_username']) || empty($_SESSION['rrm_alias'])) {
    header("location: /rrm/login");
}

$item = substr($_GET['id'], 2);

$_NAME = "";
$_DATE = "";
$_SHORT_DESC = "";
$_CONTACT = "";

$lead = "";

$getData = "SELECT rrm_oppo.*, rrm_leads.name FROM rrm_oppo INNER JOIN rrm_leads ON rrm_oppo.lead = rrm_leads.id WHERE rrm_oppo.id = '".$item."' LIMIT 1";
if ($gotData = mysqli_query($conn, $getData)) {
    if ($row = mysqli_fetch_assoc($gotData)) {
        $_DATE = $row['date'];
        $_SHORT_DESC = $row['short_desc'];
        $_CONTACT = $row['contact'];
        $_NAME = $row['name'];
        $lead = $row['lead'];
    }
}

$contact_types = array();
$getContact = "SELECT id, name, created_by FROM rrm_contacts WHERE created_by = '".$_SESSION['rrm_username']."' OR created_by = 'system' ORDER BY name ASC";
if ($gotContact = mysqli_query($conn, $getContact)) {
    while ($row = mysqli_fetch_assoc($gotContact)) {
        $contact_types[$row['id']] = $row['name'];
    }
}

if (isset($_POST['update'])) {
    $contact = $_POST['contact'];
    $short_desc = $_POST['short_desc'];
    $date = $_POST['date'];
    
    $update = "UPDATE rrm_oppo SET contact = ?, short_desc = ?, date = ? WHERE id = ?";

    if ($updateStmt = mysqli_prepare($conn, $update)) {
        mysqli_stmt_bind_param($updateStmt, "issi", $contact, $short_desc, $date, $item);
        if (mysqli_stmt_execute($updateStmt)) {
            header("location: ../?id=LD".$lead);
        }
        mysqli_stmt_close($updateStmt);
    }
}

if (isset($_POST['delete'])) {
    $delete = "DELETE FROM rrm_oppo WHERE id = ?";

    if ($deleteStmt = mysqli_prepare($conn, $delete)) {
        mysqli_stmt_bind_param($deleteStmt, "i", $item);
        if (mysqli_stmt_execute($deleteStmt)) {
            header("location: ../?id=LD".$lead);
        }
        mysqli_stmt_close($deleteStmt);
    }
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Opportunity | RRM</title>
        <link rel="icon" href="../../../RRM.png" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f2f2f2;
                padding: 0;
                margin: 0;
            }
            .top-bar {
                background-color: white;
                padding: 10px 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: fixed;
                top: 0;
                width: calc(100vw - 60px);
                height: 30px;
            }
    
            .logo {
                display: flex;
                align-items: center;
            }
    
            .logo img {
                width: 40px;
                height: auto;
                margin-right: 10px;
            }
    
            /* Dropdown button */
            .dropbtn {
              background: transparent;
              padding: 12px;
              font-size: 0.8rem;
              border: none;
              cursor: pointer;
            }
            
            /* Dropdown content (hidden by default) */
            .dropdown-content {
              display: none;
              position: absolute;
              background-color: #f9f9f9;
              min-width: 120px;
              box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
              z-index: 1;
            }
            
            /* Links inside the dropdown */
            .dropdown-content a {
              color: black;
              padding: 12px 16px;
              text-decoration: none;
              display: block;
              font-size: 0.8rem;
            }
            
            /* Change color of dropdown links on hover */
            .dropdown-content a:hover {
              background-color: #ddd;
            }
            
            /* Show the dropdown menu on hover */
            .dropdown:hover .dropdown-content {
              display: block;
            }
            
            .container {
                margin-top: 80px;
                margin-left: 30px;
                margin-right: 30px;
                width: calc(100% - 60px);
            }
            
            .modern-table {
              width: 100%;
              border-collapse: collapse;
              border-radius: 8px;
              box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
              font-family: Arial, sans-serif;
            }
            
            .modern-table th,
            .modern-table td {
              padding: 12px;
              text-align: left;
            }
            
            .modern-table th {
              background-color: #6a1b9a;
              color: white;
              font-weight: 300;
            }
            
            .modern-table th:first-child {
              border-top-left-radius: 8px;
            }
            
            .modern-table th:last-child {
              border-top-right-radius: 8px;
            }

            .modern-table tr:nth-child(even) {
              background-color: #f2f2f2;
            }
            
            .modern-table td a {
                text-decoration: underline;
                color: #8c3fa5;
                cursor: pointer;
            }
            
            .h3 {
                font-size: 1.2rem;
            }
            
            .form-container {
              width: calc(100% - 60px);
              max-width: 400px;
              margin-left: 30px;
              margin-top: 80px;
            }
            
            .modern-form {
              background-color: white;
              padding: 20px;
              border-radius: 8px;
              box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            
            .modern-form h2 {
              margin-top: 0;
              margin-bottom: 20px;
              color: #333;
            }
            
            .form-group {
              margin-bottom: 20px;
            }
            
            label {
              display: block;
              margin-bottom: 5px;
              color: #555;
            }
            
            input[type="text"],
            input[type="date"] {
              width: 100%;
              padding: 10px;
              border: 1px solid #ccc;
              border-radius: 4px;
              transition: 0.2s all;
            }
            
            input[type="text"]:focus,
            .custom-select select:focus,
            input[type="date"]:focus {
                outline: 1px solid #7a2fbc;
            }

            button {
              padding: 6px 10px;
              border: none;
              border-radius: 4px;
              background-color: #6a1b9a;
              cursor: pointer;
              font-size: 1rem;
            }
            .update_btn {
                color: white;
                margin-top: 30px;
            }
            .update_btn:hover {
              background-color: #8c3fa5;
            }
            
            .delete_btn {
                background: #D3D3D3;
            }
            
            .custom-select select {
              width: 100%;
              padding: 10px;
              border: 1px solid #ccc;
              border-radius: 4px;
              appearance: none; /* Hide default arrow */
              -webkit-appearance: none;
              -moz-appearance: none;
              background-color: white;
              cursor: pointer;
            }
            
            /* Custom styles for the select */
            .custom-select::before {
              content: '\f078'; /* Unicode for down arrow */
              font-family: 'Font Awesome 5 Free'; /* Ensure Font Awesome is loaded */
              position: absolute;
              right: 10px;
              top: 50%;
              transform: translateY(-50%);
              pointer-events: none;
            }
            
            .custom-select select:focus + ::before {
              color: #6a1b9a; /* Change arrow color on focus */
            }
            
            .custom-alert {
              display: none;
              position: fixed;
              z-index: 1;
              left: 0;
              top: 0;
              width: 100%;
              height: 100%;
              overflow: auto;
              background-color: rgba(0, 0, 0, 0.4);
            }
            
            .custom-alert-content {
              background-color: #fefefe;
              margin: 15% auto;
              padding: 20px;
              border: 1px solid #888;
              width: 80%;
              max-width: 400px;
              border-radius: 5px;
              box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            }
            
            .close {
              color: #aaaaaa;
              float: right;
              font-size: 28px;
              font-weight: bold;
              cursor: pointer;
            }
            
            .close:hover,
            .close:focus {
              color: #000;
              text-decoration: none;
            }
            
            @media screen and (max-width: 500px) {
                .form-container {
                  width: calc(100% - 20px);
                  margin-left: 10px;
                  margin-top: 60px;
                }
            }
        </style>
    </head>
    <body>
        <div class="top-bar">
            <div class="logo">
                <img src="../../../RRM.png" alt="Site Logo">
                <h2>RRM</h2>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn"><?php echo $_SESSION['rrm_alias']; ?></button>
              <div class="dropdown-content">
                <a href="../../../settings">Settings</a>
                <a href="../../../logout">Logout</a>
              </div>
            </div>
        </div>
        
        <div class="form-container">
            <form class="modern-form" method="post">
              <h3>Opportunity</h3>
              <div class="form-group">
                <label for="lead">Lead</label>
                <input type="text" id="lead" name="lead" required readonly value="<?php echo $_NAME; ?>">
              </div>
              <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required value="<?php echo $_DATE; ?>">
              </div>
              <div class="form-group">
                <label for="short_desc">Short Description</label>
                <input type="text" id="short_desc" name="short_desc" required value="<?php echo $_SHORT_DESC; ?>" maxlength=120>
              </div>
              <div class="form-group">
                <label for="message">Contact Type</label>
                <div class="custom-select">
                    <select name="contact">
                        <?php
                        echo '<option value="'.$_CONTACT.'">'.$contact_types[$_CONTACT].'</option>';
                        foreach ($contact_types as $index=>$row) {
                            if ($index == $_CONTACT) {
                                continue;
                            }
                            echo '<option value="'.$index.'">'.$row.'</option>';
                        }
                        ?>
                    </select>
              </div>
              
              <button type="submit" class="update_btn" name="update">Update</button>
              <button type="submit" name="delete" style="display: none;" id="delete_btn"></button>
              <button type="button" class="delete_btn" onclick="showCustomAlert()">Delete</button>
            </form>
        </div>
        
        <div id="customAlert" class="custom-alert">
          <div class="custom-alert-content">
            <span class="close" onclick="closeCustomAlert()">&times;</span>
            <p>Delete this record?</p>
            <button onclick="getResponse()" class="update_btn">Yes</button>
          </div>
        </div>
        
        <script>
            function showCustomAlert() {
              document.getElementById("customAlert").style.display = "block";
            }
            
            function closeCustomAlert() {
              document.getElementById("customAlert").style.display = "none";
            }
            
            function getResponse() {
                document.getElementById("delete_btn").click();
            }
        </script>
    </body>
</html>
