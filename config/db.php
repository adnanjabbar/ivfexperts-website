<?php
$host = "localhost";
$dbname = "u400207225_adnanjabbar";
$username = "u400207225_adnanjabbar";
$password = "4991701AdnanJabbar";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
