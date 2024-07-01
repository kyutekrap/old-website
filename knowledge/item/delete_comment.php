<?php
session_start();

$conn = mysqli_connect(
  '',
  '',
  '',
  '');

error_reporting(E_ERROR | E_PARSE);

$id = $_POST['id'];

$deleteQuery = "DELETE FROM rrm_articles_comments WHERE id = ?";

if ($deleteStmt = mysqli_prepare($conn, $deleteQuery)) {
    mysqli_stmt_bind_param($deleteStmt, "i", $id);
    if (mysqli_stmt_execute($deleteStmt)) {
        echo 200;
    }
    mysqli_stmt_close($deleteStmt);
}
?>