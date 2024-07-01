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

date_default_timezone_set('UTC');

$contacts = array();
$getContactsQuery = "SELECT * FROM rrm_contacts WHERE created_by = ? OR created_by = 'system' ORDER BY name ASC";

if ($getContactsStmt = mysqli_prepare($conn, $getContactsQuery)) {
    $username = $_SESSION['rrm_username'];
    mysqli_stmt_bind_param($getContactsStmt, "s", $username);
    mysqli_stmt_execute($getContactsStmt);
    $gotContacts = mysqli_stmt_get_result($getContactsStmt);

    while ($row = mysqli_fetch_assoc($gotContacts)) {
        array_push($contacts, array(
            "contact_id" => $row['contact_id'].$row['id'],
            "name" => $row['name'],
            "short_desc" => $row['short_desc'],
            "created_by" => $row['created_by'],
            "created_on" => date('Y-m-d', $row['created_on']),
            "updated_on" => $row['updated_on'],
            "active" => $row['active']
        ));
    }

    mysqli_stmt_close($getContactsStmt);
}
?>
<html>
    <head>
        <title>Portal Home | RRM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../RRM.png" />
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
                width: calc(100% - 60px);
                height: 30px;
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
    
            .logo {
                display: flex;
                align-items: center;
            }
    
            .logo img {
                width: 40px;
                height: auto;
                margin-right: 10px;
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
              background-color: #e6e6e6;
            }
            
            .modern-table td a {
                text-decoration: underline;
                color: #8c3fa5;
                cursor: pointer;
            }
            
            .h3 {
                font-size: 1.2rem;
            }
            
            .new_button {
              margin-top: 20px;
              margin-bottom: 30px;
              padding: 6px 10px;
              border: none;
              border-radius: 4px;
              background-color: #6a1b9a;
              color: white;
              cursor: pointer;
              font-size: 1rem;
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
            
            .container_tb {
                overflow-x: scroll;
            }
        </style>
    </head>
    <body>
        <div class="top-bar">
            <div class="logo">
                <img src="../RRM.png" alt="Site Logo">
                <h2>RRM</h2>
            </div>
            <div class="dropdown">
              <button class="dropbtn"><?php echo $_SESSION['rrm_alias']; ?></button>
              <div class="dropdown-content">
                <a href="../settings">Settings</a>
                <a href="../logout">Logout</a>
              </div>
            </div>
        </div>
        
        <div class="container">
            <div class="container_txt">
                <div class="h3">Contact Method</div>
                <button class="new_button"><a href="./new">Create New</a></button>
            </div>
            <div class="container_tb">
                <table class="modern-table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Short description</th>
                      <th>Created by</th>
                      <th>Created on</th>
                      <th>Updated on</th>
                      <th>Active</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      foreach ($contacts as $key=>$row) {
                          $created_by = "system";
                          if ($row['created_by'] != $created_by) {
                              $created_by = "user";
                          }
                          $active = "True";
                          if ($row['active'] == 0) {
                              $active = "False";
                          }
                          $updated_on = $row['updated_on'];
                          if ($updated_on != "") {
                              $updated_on = date('Y-m-d', $row['updated_on']);
                          }
                          $contact_id = $row['contact_id'];
                          echo '
                          <tr>
                              <td><a href="./item?id='.$contact_id.'">'.$row['contact_id'].'<a/></td>
                              <td>'.$row['name'].'</td>
                              <td>'.$row['short_desc'].'</td>
                              <td>'.$created_by.'</td>
                              <td>'.$row['created_on'].'</td>
                              <td>'.$updated_on.'</td>
                              <td>'.$active.'</td>
                          </tr>
                          ';
                      }
                      ?>
                  </tbody>
                </table>
            </div>

        </div>
    </body>
</html>
