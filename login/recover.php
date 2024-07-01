<?php
session_start();

$conn = mysqli_connect(
  '',
  '',
  '',
  '');

error_reporting(E_ERROR | E_PARSE);

$email = $_POST['email'];


?>