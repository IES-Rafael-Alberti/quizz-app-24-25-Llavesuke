<?php
require_once dirname(__FILE__) . '/../../config/config.php';
require_once dirname(__FILE__) . '/../models/Quiz.php';
require_once dirname(__FILE__) . '/../models/Question.php';

class QuizController {
    private $db;
    private $quiz;
    private $question;

    public function __construct() {
        global $conn;
        $this->db = $conn;
        $this->quiz = new Quiz($this->db);
        $this->question = new Question($this->db);
    }

    public function addQuestion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->question->quiz_id = $_POST['quiz_id'];
            $this->question->question_text = $_POST['question_text'];
            $this->question->option_a = $_POST['option_a'];
            $this->question->option_b = $_POST['option_b'];
            $this->question->option_c = $_POST['option_c'];
            $this->question->option_d = $_POST['option_d'];
            $this->question->correct_option = $_POST['correct_option'];
            if ($this->question->add()) {
                echo "Pregunta añadida exitosamente";
            } else {
                echo "Error al añadir la pregunta";
            }
        }
    }

    public function takeQuiz($quiz_id) {
        $questions = $this->question->getByQuizId($quiz_id);
        return $questions;
    }

    public function submitQuiz($quiz_id) {
        $questions = $this->question->getByQuizId($quiz_id);
        $score = 0;
        while ($question = $questions->fetch_assoc()) {
            if ($_POST['question_' . $question['question_id']] == $question['correct_option']) {
                $score++;
            }
        }
        return $score;
    }
}