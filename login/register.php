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
$email = $_POST['email'];

$findUserQuery = "SELECT * FROM rrm_users WHERE username = ? OR email = ? LIMIT 1";

if ($findUserStmt = mysqli_prepare($conn, $findUserQuery)) {
    mysqli_stmt_bind_param($findUserStmt, "ss", $username, $email);
    mysqli_stmt_execute($findUserStmt);
    mysqli_stmt_store_result($findUserStmt);

    if (mysqli_stmt_num_rows($findUserStmt) == 1) {
        echo "User exists";
    } else {
        $prefix = 'user';
        $alias = $prefix . rand(10000, 99999);
        
        $insertUserQuery = "INSERT INTO rrm_users (`username`, `password`, `email`, `alias`) VALUES (?, ?, ?, ?)";
        if ($insertUserStmt = mysqli_prepare($conn, $insertUserQuery)) {
            mysqli_stmt_bind_param($insertUserStmt, "ssss", $username, $password, $email, $alias);
            if (mysqli_stmt_execute($insertUserStmt)) {
                $_SESSION['rrm_alias'] = $alias;
                $_SESSION['rrm_username'] = $username;
                echo 200;
            } else {
                echo "Network error";
            }
        }
        mysqli_stmt_close($insertUserStmt);
    }
    mysqli_stmt_close($findUserStmt);
}
?>