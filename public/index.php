<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../app/controllers/AuthController.php');
require_once(__DIR__ . '/../app/controllers/QuizController.php');

global $conn;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['checked_rememberme']) && isset($_COOKIE['rememberme'])) {
    error_log("Remember Me cookie found: " . $_COOKIE['rememberme']);
    $token = $_COOKIE['rememberme'];
    $query = "SELECT * FROM Usuarios WHERE remember_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        session_unset();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['checked_rememberme'] = true;
        error_log("User found: " . $user['username']);
        error_log("Session variables set: username=" . $_SESSION['username'] . ", role=" . $_SESSION['role'] . ", user_id=" . $_SESSION['user_id']);
        header("Location: /public/index.php?controller=quiz&action=getAllQuizzes");
        exit();
    } else {
        error_log("User not found.");
    }
} else {
    if (isset($_SESSION['username'])) {
        error_log("Session already set: username=" . $_SESSION['username']);
    }
}

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

if (($controller == 'auth' && $action == 'login') || ($controller == 'auth' && $action == 'register') || ($controller == 'auth' && $action == 'logout') || ($controller == 'auth' && $action == '') || ($controller == '' && $action == '') || ($_SERVER['REQUEST_URI'] == '/')) {
    // Borrar la sesión y la cookie PHPSESSID
    session_unset();
    session_destroy();

    // Borrar la cookie PHPSESSID
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_start();
    error_log("Session cleared for login, register, logout, or root action");
}

ob_start();

switch ($controller) {
    case 'auth':
        $authController = new AuthController();
        if ($action == 'login') {
            $authController->login();
        } elseif ($action == 'register') {
            $authController->register();
        } elseif ($action == 'logout') {
            $authController->logout();
        }
        break;
    case 'quiz':
        if (!isset($_SESSION['username'])) {
            header("Location: /public/index.php?controller=auth&action=login");
            exit();
        }
        $quizController = new QuizController();
        if ($action == 'submitQuiz') {
            error_log("Routing to submitQuiz");
            $quizController->submitQuiz($_GET['quiz_id']);
        } elseif ($action == 'takeQuiz') {
            $quizController->takeQuiz($_GET['quiz_id']);
        } elseif ($action == 'summary') {
            $quizController->summary();
        } elseif ($action == 'getAllQuizzes') {
            $quizController->getAllQuizzes();
        }
        break;
    default:
        echo "Controller not found";
        break;
}

$content = ob_get_clean();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Application</title>
    <link href="/quiz.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="app"><?php echo $content; ?></div>
</body>
</html>