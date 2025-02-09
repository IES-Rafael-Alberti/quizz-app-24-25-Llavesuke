<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/public/quiz.css">
</head>
<body>
<h2>Login</h2>
<form method="POST" action="/public/index.php?controller=auth&action=login">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <input type="hidden" name="action" value="login">
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="/public/index.php?controller=auth&action=register">Register here</a></p>
</body>
</html>