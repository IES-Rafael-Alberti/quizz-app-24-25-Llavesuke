<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <link rel="stylesheet" href="/public/quiz.css">
</head>
<body>
<h2>Create Quiz</h2>
<form method="POST" action="/public/index.php?controller=quiz&action=createQuiz">
    <input type="hidden" name="action" value="createQuiz">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>
    <br>
    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea>
    <br>
    <button type="submit">Create Quiz</button>
</form>
</body>
</html>