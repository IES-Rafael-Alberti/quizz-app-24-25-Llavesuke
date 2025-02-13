
<h2>Manage Quizzes</h2>
<a href="/public/index.php?controller=quiz&action=createQuizForm">Create New Quiz</a>
<table>
    <thead>
    <tr>
        <th>Quiz ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($quiz = $quizzes->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($quiz['quiz_id']); ?></td>
            <td><?php echo htmlspecialchars($quiz['title']); ?></td>
            <td><?php echo htmlspecialchars($quiz['description']); ?></td>
            <td>
                <a href="/public/index.php?controller=quiz&action=editQuizForm&quiz_id=<?php echo $quiz['quiz_id']; ?>">Edit</a>
                <a href="/public/index.php?controller=quiz&action=deleteQuiz&quiz_id=<?php echo $quiz['quiz_id']; ?>">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
