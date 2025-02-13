
<h2>Edit Question</h2>
<form method="POST" action="/public/index.php?controller=quiz&action=updateQuestion&question_id=<?php echo $question['question_id']; ?>">
    <input type="hidden" name="action" value="updateQuestion">
    <input type="hidden" name="quiz_id" value="<?php echo $question['quiz_id']; ?>">
    <label for="question_text">Question Text:</label>
    <textarea id="question_text" name="question_text" required><?php echo htmlspecialchars($question['question_text']); ?></textarea>
    <br>
    <label for="option_a">Option A:</label>
    <input type="text" id="option_a" name="option_a" value="<?php echo htmlspecialchars($question['option_a']); ?>" required>
    <br>
    <label for="option_b">Option B:</label>
    <input type="text" id="option_b" name="option_b" value="<?php echo htmlspecialchars($question['option_b']); ?>" required>
    <br>
    <label for="option_c">Option C:</label>
    <input type="text" id="option_c" name="option_c" value="<?php echo htmlspecialchars($question['option_c']); ?>" required>
    <br>
    <label for="option_d">Option D:</label>
    <input type="text" id="option_d" name="option_d" value="<?php echo htmlspecialchars($question['option_d']); ?>" required>
    <br>
    <label for="correct_option">Correct Option:</label>
    <select id="correct_option" name="correct_option" required>
        <option value="a" <?php if ($question['correct_option'] == 'a') echo 'selected'; ?>>A</option>
        <option value="b" <?php if ($question['correct_option'] == 'b') echo 'selected'; ?>>B</option>
        <option value="c" <?php if ($question['correct_option'] == 'c') echo 'selected'; ?>>C</option>
        <option value="d" <?php if ($question['correct_option'] == 'd') echo 'selected'; ?>>D</option>
    </select>
    <br>
    <button type="submit">Update Question</button>
</form>
