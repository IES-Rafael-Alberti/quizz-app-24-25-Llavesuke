
<h2>Add Question</h2>
<form method="POST" action="/public/index.php?controller=quiz&action=addQuestion">
    <input type="hidden" name="action" value="addQuestion">
    <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($_GET['quiz_id']); ?>">
    <label for="question_text">Question Text:</label>
    <textarea id="question_text" name="question_text" required></textarea>
    <br>
    <label for="option_a">Option A:</label>
    <input type="text" id="option_a" name="option_a" required>
    <br>
    <label for="option_b">Option B:</label>
    <input type="text" id="option_b" name="option_b" required>
    <br>
    <label for="option_c">Option C:</label>
    <input type="text" id="option_c" name="option_c" required>
    <br>
    <label for="option_d">Option D:</label>
    <input type="text" id="option_d" name="option_d" required>
    <br>
    <label for="correct_option">Correct Option:</label>
    <select id="correct_option" name="correct_option" required>
        <option value="a">A</option>
        <option value="b">B</option>
        <option value="c">C</option>
        <option value="d">D</option>
    </select>
    <br>
    <button type="submit">Add Question</button>
</form>
