<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'header.php';
?>

<div class="content">
    <h2>Welcome, <?php echo $_SESSION['user']; ?> 👋</h2>
    <p>You are logged in.</p>
    <a href="logout.php">Logout</a>
</div>

<?php include 'footer.php'; ?>

