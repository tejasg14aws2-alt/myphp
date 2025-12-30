<?php
session_start();

$correct_user = "admin";
$correct_pass = "password123";

$username = $_POST['username'];
$password = $_POST['password'];

if ($username == $correct_user && $password == $correct_pass) {
    $_SESSION['user'] = $username;
    header("Location: dashboard.php");
    exit;
} else {
    echo "❌ Invalid login. <a href='login.php'>Try again</a>";
}
?>

