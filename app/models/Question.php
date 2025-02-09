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
        $query = "SELECT *, 
              CASE correct_option 
                WHEN 'a' THEN option_a 
                WHEN 'b' THEN option_b 
                WHEN 'c' THEN option_c 
                WHEN 'd' THEN option_d 
              END AS correct_option_text 
              FROM " . $this->table . " WHERE quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getById($question_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE question_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ? WHERE question_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssi", $this->question_text, $this->option_a, $this->option_b, $this->option_c, $this->option_d, $this->correct_option, $this->question_id);
        return $stmt->execute();
    }

    public function delete($question_id) {
        $query = "DELETE FROM " . $this->table . " WHERE question_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $question_id);
        return $stmt->execute();
    }

    public function getQuizIdByQuestionId($question_id) {
        $query = "SELECT quiz_id FROM " . $this->table . " WHERE question_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['quiz_id'];
    }
}
?>