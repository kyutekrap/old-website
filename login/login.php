<?php
session_start();

$conn = mysqli_connect(
    '',
    '',
    '',
    '');

error_reporting(E_ERROR | E_PARSE);

$username = $_POST['username'];
$password = $_POST['password'];
$password = hash('sha256', $password);

$findUser = "SELECT * FROM rrm_users WHERE username = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $findUser);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];
        
        // Verify password
        if ($password === $hashed_password) {
            $_SESSION['rrm_alias'] = $row['alias'];
            $_SESSION['rrm_username'] = $row['username'];
            echo 200; // Successful login
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Invalid username";
    }
} else {
    echo "Network error";
}
?>