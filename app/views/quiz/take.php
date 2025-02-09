
<form method="POST" action="/public/index.php?controller=quiz&action=submitQuiz&quiz_id=<?php echo $quiz_id; ?>">
    <h1>PHP Quiz</h1>

    <?php while ($question = $questions->fetch_assoc()): ?>
        <div class="question">
            <p><?php echo htmlspecialchars($question['question_text']); ?></p>
            <label><input type="radio" name="question_<?php echo $question['question_id']; ?>" value="<?php echo htmlspecialchars($question['option_a']); ?>" required> a) <?php echo htmlspecialchars($question['option_a']); ?></label><br>
            <label><input type="radio" name="question_<?php echo $question['question_id']; ?>" value="<?php echo htmlspecialchars($question['option_b']); ?>" required> b) <?php echo htmlspecialchars($question['option_b']); ?></label><br>
            <label><input type="radio" name="question_<?php echo $question['question_id']; ?>" value="<?php echo htmlspecialchars($question['option_c']); ?>" required> c) <?php echo htmlspecialchars($question['option_c']); ?></label><br>
            <label><input type="radio" name="question_<?php echo $question['question_id']; ?>" value="<?php echo htmlspecialchars($question['option_d']); ?>" required> d) <?php echo htmlspecialchars($question['option_d']); ?></label><br>
        </div>
    <?php endwhile; ?>

    <input type="submit" value="Submit">
</form>
