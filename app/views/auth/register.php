<h2>Register</h2>
<form method="POST" action="/public/index.php?controller=auth&action=register">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <label for="rememberme">Remember Me:</label>
    <input type="checkbox" id="rememberme" name="rememberme">
    <br>
    <input type="hidden" name="action" value="register">
    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="/public/index.php?controller=auth&action=login">Login here</a></p>