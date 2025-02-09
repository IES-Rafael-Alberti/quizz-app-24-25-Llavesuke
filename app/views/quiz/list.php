
<h1>Available Quizzes</h1>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="/public/index.php?controller=quiz&action=manageQuizzes">Manage Quizzes</a>
<?php endif; ?>

<table>
    <thead>
    <tr>
        <th>Quiz ID</th>
        <th>Title</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($quiz = $quizzes->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($quiz['quiz_id']); ?></td>
            <td><a href="/public/index.php?controller=quiz&action=takeQuiz&quiz_id=<?php echo $quiz['quiz_id']; ?>"><?php echo htmlspecialchars($quiz['title']); ?></a></td>
            <td><?php echo htmlspecialchars($quiz['description']); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<form method="POST" action="/public/index.php?controller=auth&action=logout">
    <button type="submit">Logout</button>
</form>
