<?php
session_start();

$conn = mysqli_connect(
    '',
    '',
    '',
    '');

error_reporting(E_ERROR | E_PARSE);

if (empty($_SESSION['rrm_username'])) {
    echo 300;
    return;
}

$username = $_SESSION['rrm_username'];
$article = $_POST['article'];

$insertQuery = "INSERT INTO rrm_articles_likes (`username`, `article`) VALUES (?, ?)";

if ($insertStmt = mysqli_prepare($conn, $insertQuery)) {
    mysqli_stmt_bind_param($insertStmt, "ss", $username, $article);
    if (mysqli_stmt_execute($insertStmt)) {
        echo 200;
    }
    mysqli_stmt_close($insertStmt);
}
?>