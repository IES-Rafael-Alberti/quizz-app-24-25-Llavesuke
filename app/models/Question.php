<?php
class Question {
    private $conn;
    private $table = 'Preguntas';

    public $question_id;
    public $quiz_id;
    public $question_text;
    public $option_a;
    public $option_b;
    public $option_c;
    public $option_d;
    public $correct_option;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add() {
        $query = "INSERT INTO " . $this->table . " (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssss", $this->quiz_id, $this->question_text, $this->option_a, $this->option_b, $this->option_c, $this->option_d, $this->correct_option);
        return $stmt->execute();
    }

    public function getByQuizId($quiz_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>