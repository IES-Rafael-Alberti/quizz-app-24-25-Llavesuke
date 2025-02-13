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
        } else {
            // Mostrar el formulario de registro si no es una solicitud POST
            include dirname(__FILE__) . '/../views/auth/register.php';
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
                // Mostrar error si las credenciales son incorrectas
                $error = "Credenciales incorrectas";
                include dirname(__FILE__) . '/../views/auth/login.php';
            }
        } else {
            // Mostrar el formulario de login si no es una solicitud POST
            include dirname(__FILE__) . '/../views/auth/login.php';
        }
    }

    public function logout() {
        // Iniciar la sesión si no está activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Borrar la cookie "rememberme" si existe
        if (isset($_COOKIE['rememberme'])) {
            // Borrar la cookie estableciendo una fecha de expiración en el pasado
            setcookie('rememberme', '', time() - 3600, '/', 'localhost', true, true);
            unset($_COOKIE['rememberme']); // Eliminar la cookie del array $_COOKIE
            error_log("Cookie 'rememberme' eliminada.");
        }

        // 2. Borrar todas las variables de sesión
        $_SESSION = []; // Vaciar el array de sesión
        error_log("Variables de sesión eliminadas.");

        // 3. Borrar la cookie de sesión si está configurada
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), // Nombre de la cookie de sesión
                '', // Valor vacío
                time() - 42000, // Fecha de expiración en el pasado
                $params["path"], // Ruta de la cookie
                $params["domain"], // Dominio de la cookie
                $params["secure"], // Segura (HTTPS)
                $params["httponly"] // Solo HTTP
            );
            error_log("Cookie de sesión eliminada.");
        }

        // 4. Destruir la sesión
        session_destroy();
        error_log("Sesión destruida.");

        // 5. Redirigir al usuario de manera segura
        // Usar una URL absoluta para evitar problemas con rutas relativas
        $redirectUrl = "/public/index.php?controller=auth&action=login";
        header("Location: " . $redirectUrl);
        error_log("Redirigiendo a: " . $redirectUrl);

        // 6. Asegurarse de que el script se detenga después de la redirección
        exit();
    }
}
?>