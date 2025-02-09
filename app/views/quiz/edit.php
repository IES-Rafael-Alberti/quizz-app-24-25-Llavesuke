
<h2>Edit Quiz</h2>
<form method="POST" action="/public/index.php?controller=quiz&action=updateQuiz&quiz_id=<?php echo $quiz['quiz_id']; ?>">
    <input type="hidden" name="action" value="updateQuiz">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required>
    <br>
    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($quiz['description']); ?></textarea>
    <br>
    <button type="submit">Update Quiz</button>
</form>

<h3>Questions</h3>
<a href="/public/index.php?controller=quiz&action=addQuestionForm&quiz_id=<?php echo $quiz['quiz_id']; ?>">Add New Question</a>
<table>
    <thead>
    <tr>
        <th>Question Text</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($questions->num_rows > 0): ?>
        <?php while ($question = $questions->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($question['question_text']); ?></td>
                <td>
                    <a href="/public/index.php?controller=quiz&action=editQuestionForm&question_id=<?php echo $question['question_id']; ?>">Edit</a>
                    <a href="/public/index.php?controller=quiz&action=deleteQuestion&question_id=<?php echo $question['question_id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="2">No questions found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
