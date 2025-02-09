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
            session_start();
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
            $this->user->username = $username;
            $this->user->password = password_hash($password, PASSWORD_BCRYPT);
            $this->user->role = 'user';
            if ($this->user->register()) {
                $_SESSION['username'] = $this->user->username;
                $_SESSION['role'] = $this->user->role;
                $user = $this->user->login();
                $_SESSION['user_id'] = $user['user_id'];

                if (isset($_POST['rememberme'])) {
                    $token = bin2hex(random_bytes(16));
                    setcookie('rememberme', $token, [
                        'expires' => time() + 86400 * 30, // 30 days
                        'path' => '/',
                        'domain' => 'localhost',
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Strict',
                    ]);
                    $query = "UPDATE Usuarios SET remember_token = ? WHERE user_id = ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->bind_param("si", $token, $user['user_id']);
                    $stmt->execute();
                }

                header("Location: /public/index.php?controller=quiz&action=getAllQuizzes");
                exit();
            } else {
                echo "Error en el registro";
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
            $this->user->username = $username;
            $user = $this->user->login();
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_id'] = $user['user_id'];

                if (isset($_POST['rememberme'])) {
                    $token = bin2hex(random_bytes(16));
                    setcookie('rememberme', $token, [
                        'expires' => time() + 86400 * 30, // 30 days
                        'path' => '/',
                        'domain' => 'localhost',
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Strict',
                    ]);
                    $query = "UPDATE Usuarios SET remember_token = ? WHERE user_id = ?";
                    $stmt = $this->db->prepare($query);
                    if ($stmt === false) {
                        die('Prepare failed: ' . htmlspecialchars($this->db->error));
                    }
                    $stmt->bind_param("si", $token, $user['user_id']);
                    $stmt->execute();
                }

                header("Location: /public/index.php?controller=quiz&action=getAllQuizzes");
                exit();
            } else {
                // Handle login failure
            }
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();

        // Remove the "Remember Me" cookie
        setcookie('rememberme', '', time() - 3600, '/', 'localhost', true, true);

        header("Location: /public/index.php?controller=auth&action=login");
        exit();
    }
}
?>