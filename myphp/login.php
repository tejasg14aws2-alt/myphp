<?php include 'header.php'; ?>

<div class="content">
    <h2>Login</h2>
    <form method="POST" action="authenticate.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
</div>

<?php include 'footer.php'; ?>

