<h1>Quiz Summary</h1>
<?php
echo "Correct answers: " . htmlspecialchars($score) . "<br>";
echo "Total questions: " . htmlspecialchars($total_questions) . "<br>";
echo "Score: " . htmlspecialchars($percentage) . "%<br>";
echo "Average score: " . htmlspecialchars(number_format($statistics['average_score'], 2)) . "%<br>";
echo "Number of attempts: " . htmlspecialchars($statistics['attempts']) . "<br>";
?>