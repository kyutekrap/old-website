<?php
session_start();

$conn = mysqli_connect(
  '',
  '',
  '',
  '');

error_reporting(E_ERROR | E_PARSE);

$username = $_SESSION['rrm_username'];
$article = $_POST['article'];

$deleteQuery = "DELETE FROM rrm_articles_likes WHERE username = ? AND article = ?";

if ($deleteStmt = mysqli_prepare($conn, $deleteQuery)) {
    mysqli_stmt_bind_param($deleteStmt, "ss", $username, $article);
    if (mysqli_stmt_execute($deleteStmt)) {
        echo 200;
    }
    mysqli_stmt_close($deleteStmt);
}
?>