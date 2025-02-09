<?php
require_once dirname(__FILE__) . '/../../config/config.php';
require_once dirname(__FILE__) . '/../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        global $conn;
        $this->db = $conn;
        $this->user = new User($this->db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            if ($this->user->register()) {
                echo "Registro exitoso";
            } else {
                echo "Error en el registro";
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->username = $_POST['username'];
            $user = $this->user->login();
            if ($user && password_verify($_POST['password'], $user['password'])) {
                session_start();
                $_SESSION['username'] = $user['username'];
                echo "Inicio de sesión exitoso";
            } else {
                echo "Credenciales incorrectas";
            }
        }
    }
}