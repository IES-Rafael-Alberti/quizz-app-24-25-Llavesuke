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

    /**
     * Handles user registration.
     */
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

                // Set "Remember Me" cookie if checked
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
        } else {
            // Show registration form if not a POST request
            include dirname(__FILE__) . '/../views/auth/register.php';
        }
    }

    /**
     * Handles user login.
     */
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

                // Set "Remember Me" cookie if checked
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
                // Show error if credentials are incorrect
                $error = "Credenciales incorrectas";
                include dirname(__FILE__) . '/../views/auth/login.php';
            }
        } else {
            // Show login form if not a POST request
            include dirname(__FILE__) . '/../views/auth/login.php';
        }
    }

    /**
     * Handles user logout.
     */
    public function logout() {
        // Start session if not active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Delete "rememberme" cookie if exists
        if (isset($_COOKIE['rememberme'])) {
            setcookie('rememberme', '', time() - 3600, '/', 'localhost', true, true);
            unset($_COOKIE['rememberme']);
            error_log("Cookie 'rememberme' eliminada.");
        }

        // 2. Clear all session variables
        $_SESSION = [];
        error_log("Variables de sesión eliminadas.");

        // 3. Delete session cookie if set
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
            error_log("Cookie de sesión eliminada.");
        }

        // 4. Destroy the session
        session_destroy();
        error_log("Sesión destruida.");

        // 5. Redirect user securely
        $redirectUrl = "/public/index.php?controller=auth&action=login";
        header("Location: " . $redirectUrl);
        error_log("Redirigiendo a: " . $redirectUrl);

        // 6. Ensure script stops after redirection
        exit();
    }
}
?>