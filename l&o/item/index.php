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

# === BASIC DATA (START)
$_NAME = "";
$_SHORT_DESC = "";
$_STAGE = "";
# === BASIC DATA (END)

$item = substr($_GET['id'], 2);
$getData = "SELECT * FROM rrm_leads WHERE id = '".$item."' LIMIT 1";
if ($gotData = mysqli_query($conn, $getData)) {
    if ($row = mysqli_fetch_assoc($gotData)) {
        $_NAME = $row['name'];
        $_SHORT_DESC = $row['short_desc'];
        $_STAGE = $row['stage'];
    }
}

# === STAGE TYPES (START)
$stage_types = ['Acquaintance', 'Friend', 'FWB', 'Exclusive', 'Broke Up'];
# === STAGE TYPES (END)

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $short_desc = $_POST['short_desc'];
    $stage = $_POST['stage'];
    $updated_on = time();
    
    $update = "UPDATE rrm_leads SET name = ?, short_desc = ?, stage = ?, updated_on = ? WHERE id = ?";

    if ($updateStmt = mysqli_prepare($conn, $update)) {
        mysqli_stmt_bind_param($updateStmt, "ssssi", $name, $short_desc, $stage, $updated_on, $item);
        if (mysqli_stmt_execute($updateStmt)) {
            $_NAME = $name;
            $_SHORT_DESC = $short_desc;
            $_STAGE = $stage;
        }
        mysqli_stmt_close($updateStmt);
    }
}

$oppo = array();

$getOppo = "SELECT rrm_oppo.*, rrm_contacts.name FROM rrm_oppo INNER JOIN rrm_contacts ON rrm_oppo.contact = rrm_contacts.id
            WHERE rrm_oppo.lead = '".$item."' ORDER BY rrm_oppo.created_on DESC";
if ($gotOppo = mysqli_query($conn, $getOppo)) {
    while ($row = mysqli_fetch_assoc($gotOppo)) {
        array_push($oppo, array("oppo_id"=>$row['oppo_id'].$row['id'], "contact"=>$row['name'], "short_desc"=>$row['short_desc'], "date"=>$row['date'], "created_on"=>date('Y-m-d', $row['created_on'])));
    }
}

if (isset($_POST['delete_btn'])) {
    $delete = "DELETE FROM rrm_leads WHERE id = ?";

    if ($deleteStmt = mysqli_prepare($conn, $delete)) {
        mysqli_stmt_bind_param($deleteStmt, "i", $item);
        if (mysqli_stmt_execute($deleteStmt)) {
            header("location: ../");
        }
        mysqli_stmt_close($deleteStmt);
    }
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lead | RRM</title>
        <link rel="icon" href="../../RRM.png" />
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
                margin: 80 30 10 30;
                width: calc(100% - 60px);
            }
            
            .modern-table {
              width: 100%;
              border-collapse: collapse;
              border-radius: 8px;
              box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
              font-family: Arial, sans-serif;
              margin-top: 20px;
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
            
            input[type="text"] {
              width: 100%;
              padding: 10px;
              border: 1px solid #ccc;
              border-radius: 4px;
              transition: 0.2s all;
            }
            
            input[type="text"]:focus,
            .custom-select select:focus {
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
                background: #D5D5D5;
            }
            
            .new_button {
                margin-top: 0px;
                color: white;
            }
            .new_button:hover {
                background-color: #8c3fa5;
            }
            .new_button a {
                color: white;
            }
            a {
                text-decoration: none;
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
            
            .container_tb {
                overflow-x: scroll;
            }
            
            @media screen and (max-width: 750px) {
                .container {
                    margin: 40 10 10 10;
                    width: calc(100% - 20px);
                }
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
                <img src="../../RRM.png" alt="Site Logo">
                <h2>RRM</h2>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn"><?php echo $_SESSION['rrm_alias']; ?></button>
              <div class="dropdown-content">
                <a href="../../settings">Settings</a>
                <a href="../../logout">Logout</a>
              </div>
            </div>
        </div>
        
        <div class="form-container">
            <form class="modern-form" method="post">
              <h3>Lead</h3>
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required value="<?php echo $_NAME; ?>" maxlength=60>
              </div>
              <div class="form-group">
                <label for="short_desc">Short Description</label>
                <input type="text" id="short_desc" name="short_desc" required value="<?php echo $_SHORT_DESC; ?>" maxlength=120>
              </div>
              <div class="form-group">
                <label for="message">Stage</label>
                <div class="custom-select">
                    <select name="stage">
                        <?php
                            echo '<option value="'.$_STAGE.'">'.$_STAGE.'</option>';
                            foreach ($stage_types as $stage) {
                                if ($stage == $_STAGE) {
                                    continue;
                                }
                                echo '<option value="'.$stage.'">'.$stage.'</option>';
                            }
                        ?>
                    </select>
              </div>
            </div>
              <button type="submit" class="update_btn" name="update">Update</button>
              <button type="submit" style="display: none;" name="delete_btn" id="delete_btn"></button>
              <button type="button" class="delete_btn" onclick="showCustomAlert()">Delete</button>
            </form>
        </div>
        
        <div class="container">
            <div class="container_txt">
                <h3>Opportunities</h3>
                <button class="new_button"><a href="./new?id=<?php echo $_GET['id']; ?>">Create New</a></button>
            </div>
            <div class="container_tb">
                <table class="modern-table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Contact Type</th>
                      <th>Short description</th>
                      <th>Date</th>
                      <th>Created on</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      foreach ($oppo as $index=>$value) {
                          echo '
                            <tr>
                              <td><a href="./item?id='.$value['oppo_id'].'">'.$value['oppo_id'].'<a/></td>
                              <td>'.$value['contact'].'</td>
                              <td>'.$value['short_desc'].'</td>
                              <td>'.$value['date'].'</td>
                              <td>'.$value['created_on'].'</td>
                            </tr>
                          ';
                      }
                      ?>
                  </tbody>
                </table>
            </div>

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

