<form method="POST" action="/public/index.php?controller=quiz&action=submitQuiz&quiz_id=<?php echo $quiz_id; ?>">
    <?php foreach ($questions as $question): ?>
        <p><?php echo $question['question_text']; ?></p>
        <label><input type="radio" name="question_<?php echo $question['question_id']; ?>" value="a"><?php echo $question['option_a']; ?></label><br>
        <label><input type="radio" name="question_<?php echo $question['question_id']; ?>" value="b"><?php echo $question['option_b']; ?></label><br>
        <label><input type="radio" name="question_<?php echo $question['question_id']; ?>" value="c"><?php echo $question['option_c']; ?></label><br>
        <label><input type="radio" name="question_<?php echo $question['question_id']; ?>" value="d"><?php echo $question['option_d']; ?></label><br>
    <?php endforeach; ?>
    <button type="submit">Enviar</button>
</form>