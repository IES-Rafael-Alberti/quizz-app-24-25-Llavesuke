<?php
require_once dirname(__FILE__) . '/../../config/config.php';
require_once dirname(__FILE__) . '/../models/User.php';

class AuthController
{
    private $db;
    private $user;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
        $this->user = new User($this->db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $this->user->role = 'user'; // Asignar el rol "user"
            if ($this->user->register()) {
                session_start();
                $_SESSION['username'] = $this->user->username;
                $_SESSION['role'] = $this->user->role; // Almacenar el rol en la sesión

                // Obtener el user_id del usuario recién registrado
                $user = $this->user->login();
                $_SESSION['user_id'] = $user['user_id']; // Almacenar el user_id en la sesión

                header("Location: /public/index.php?controller=quiz&action=getAllQuizzes");
                exit();
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
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_id'] = $user['user_id']; // Almacenar el user_id en la sesión
                header("Location: /public/index.php?controller=quiz&action=getAllQuizzes");
                exit();
            } else {
                echo "Credenciales incorrectas";
            }
        }
    }
}
?>