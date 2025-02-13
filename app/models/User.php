<?php
class User {
    private $conn;
    private $table = 'Usuarios';

    public $user_id;
    public $username;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registers a new user in the database.
     */
    public function register() {
        $query = "INSERT INTO " . $this->table . " (username, password, role) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $this->username, $this->password, $this->role);
        return $stmt->execute();
    }

    /**
     * Logs in a user by retrieving their information from the database.
     */
    public function login() {
        $query = "SELECT * FROM " . $this->table . " WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>