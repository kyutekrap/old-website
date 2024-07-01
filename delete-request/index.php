<?php
session_start();

$conn = mysqli_connect(
    '',
    '',
    '',
    '');

error_reporting(E_ERROR | E_PARSE);

function log_error($message) {
    // Define the log file path
    $logFilePath = 'ACTION_REQUIRED.log';

    // Get the current timestamp
    $timestamp = date('Y-m-d H:i:s');

    // Format the log entry
    $logEntry = "[$timestamp] $message" . PHP_EOL;

    // Open the log file in append mode or create it if it doesn't exist
    $file = fopen($logFilePath, 'a');

    if ($file) {
        // Write the log entry to the file
        fwrite($file, $logEntry);

        // Close the file handle
        fclose($file);
    }
}

$display = "none";
$color = "red";
$clientAlert = "";

if (isset($_POST['delete'])) {
    $username = $_POST['username'];
    $password = hash('sha256',$_POST['password']);
    
    $verifyUser = "SELECT id FROM rrm_users WHERE username = '".$username."' AND password = '".$password."' LIMIT 1";
    if ($verifiedUser = mysqli_query($conn, $verifyUser)) {
        if (mysqli_num_rows($verifiedUser) == 1) {
            
            $row = mysqli_fetch_assoc($verifiedUser);
            
            $message = "ACTION REQUIRED FOR: " + $username + ". ACTION REQUIRED IN: ";
            
            // 1. DELETE LIKES
            $delete = "DELETE FROM rrm_articles_likes WHERE username = '".$username."'";
            if (!mysqli_query($conn, $delete)) {
                log_error($message."rrm_articles_likes");
            }
            
            // 2. DELETE COMMENTS
            $delete = "DELETE FROM rrm_articles_comments WHERE username = '".$username."'";
            if (!mysqli_query($conn, $delete)) {
                log_error($message."rrm_articles_comments");
            }
            
            // 3. DELETE ARTICLES
            $delete = "DELETE FROM rrm_articles WHERE created_by = '".$row['id']."'";
            if (!mysqli_query($conn, $delete)) {
                log_error($message."rrm_articles");
            }
            
            // 4. DELETE CONTACTS
            $delete = "DELETE FROM rrm_contacts WHERE created_by = '".$username."'";
            if (!mysqli_query($conn, $delete)) {
                log_error($message."rrm_contacts");
            }
            
            // 5. DELETE OPPO
            $delete = "DELETE rrm_oppo FROM rrm_oppo
                        INNER JOIN rrm_leads ON rrm_oppo.lead = rrm_leads.id
                        WHERE rrm_leads.created_by = '".$username."'";
            if (!mysqli_query($conn, $delete)) {
                log_error($message."rrm_oppo");
            }
            
            // 6. DELETE LEADS
            $delete = "DELETE FROM rrm_leads WHERE created_by = '".$username."'";
            if (!mysqli_query($conn, $delete)) {
                log_error($message."rrm_leads");
            }
            
            $delete = "DELETE FROM rrm_users WHERE username = '".$username."'";
            if (!mysqli_query($conn, $delete)) {
                log_error($message."rrm_users");
            }
            
            $display = "block";
            $display = "green";
            $clientAlert = "Action completed.";
            
        } else {
            $display = "block";
            $display = "red";
            $clientAlert = "User does not exist.";
        }
    }
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RRM | Delete Request</title>
        <link rel="icon" href="../RRM.png" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        
        <style>
            * {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                overflow: hidden;
            }
            .first_page {
                width: calc(100vw - 60px);
                height: calc(100vh - 36px);
                justify-content: center;
                align-items: center;
                display: flex;
                padding-left: 30px;
                padding-right: 30px;
            }
            .middle_content {
                
            }
            .h1 {
                font-size: 2.6rem;
                font-weight: 600;
            }
            .h3 {
                font-size: 1.4rem;
            }
            .big_btn {
                padding: 10px 20px;
                font-size: 1.2rem;
                text-decoration: none;
                color: white;
                border: none;
                border-radius: 5px;
                background-color: #6a1b9a;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .big_btn:hover {
                background-color: #4a148c;
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
                z-index: 3;
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
    
            .login-btn {
                padding: 8px 20px;
                color: white;
                font-size: 0.8rem;
                border: none;
                border-radius: 5px;
                background-color: #6a1b9a;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
    
            .login-btn:hover {
                background-color: #4a148c;
            }
            
            .footer {
                width: 100vw;
                text-align: center;
                color: white;
                background: #360d4d;
                padding: 10 0 10 0;
            }
            .footer p {
                font-size: 12;
            }
            
            a {
                color: white;
                text-decoration: none;
            }
            
            input[type="text"],
            input[type="password"] {
              padding: 10px;
              margin-bottom: 15px;
              border-radius: 5px;
              border: 1px solid #ccc;
              font-size: 16px;
            }
            input[type="submit"] {
              padding: 10px;
              background-color: #007bff;
              color: #fff;
              border: none;
              border-radius: 5px;
              cursor: pointer;
              margin-bottom: 15px;
              transition: background-color 0.3s;
            }
            input[type="submit"]:hover {
              background-color: #0056b3;
            }
    
            /* Responsive styles */
            @media screen and (max-width: 768px) {
                .feature {
                    width: calc(100% - 60px);
                    margin: 30 0 0 0;
                }
                .features-container {
                    width: 80%;
                    margin-left: 10%;
                    margin-top: 10px;
                }
            }
    
            /* Responsive styles */
            @media screen and (max-width: 600px) {
                .logo img {
                    width: 30px;
                }

                .login-btn {
                    padding: 6px 15px;
                }
                
                .second_page {
                    padding-left: 10px;
                    padding-right: 10px;
                    width: calc(100% - 20px);
                }
            }
        </style>
    </head>
    <body>
        <div class="top-bar">
            <div class="logo">
                <img src="../RRM.png" alt="Site Logo">
                <h2>RRM</h2>
            </div>
            
            <div>
                <button class="login-btn"><a href="./login">Client Login</a></button>
            </div>
        </div>
        
        <section class="first_page">
            <div class="middle_content">
                <div class="h1">Erase your account here.</div>
                <br/>
                <div class="h3">Your account, articles, comments, records will be deleted.</div>
                <div><small>It may take 24 hours before every data is deleted.</small></div>
                <br/>
                <form method="post">
                    <input placeholder="Username" type="text" name="username" required />
                    <br/>
                    <input placeholder="Password" type="password" name="password" required />
                    <br/>
                    <input type="submit" value="Request Delete" name="delete" />
                </form>
                <div style="display: <?php echo $display; ?>"><small style="color: <?php echo $color; ?>;"><?php echo $clientAlert; ?></small></div>
            </div>
        </section>
        
        <div class="footer">
            <p>© Copyright 2023 Dong Kye Technology. All rights reserved.</p>
        </div>
    </body>
</html>