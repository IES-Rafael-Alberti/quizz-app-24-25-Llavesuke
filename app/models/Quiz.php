<?php
class Quiz {
    private $conn;
    private $table = 'Cuestionarios';

    public $quiz_id;
    public $title;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (title, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $this->title, $this->description);
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    public function getById($quiz_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET title = ?, description = ? WHERE quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $this->title, $this->description, $this->quiz_id);
        return $stmt->execute();
    }

    public function delete($quiz_id) {
        $query = "DELETE FROM " . $this->table . " WHERE quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        return $stmt->execute();
    }
}
?>