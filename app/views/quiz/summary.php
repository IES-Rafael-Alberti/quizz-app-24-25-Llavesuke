
<h1>Quiz Summary</h1>
<?php
echo "Respuestas correctas: " . htmlspecialchars($score) . "<br>";
echo "Total de preguntas: " . htmlspecialchars($total_questions) . "<br>";
echo "Puntuación: " . htmlspecialchars($percentage) . "%<br>";
echo "Puntuación media: " . htmlspecialchars(number_format($statistics['average_score'], 2)) . "%<br>";
echo "Número de intentos: " . htmlspecialchars($statistics['attempts']) . "<br>";
?>

