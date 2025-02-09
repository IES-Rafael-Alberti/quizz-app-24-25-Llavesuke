<?php

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../app/controllers/AuthController.php');
require_once(__DIR__ . '/../app/controllers/QuizController.php');

global $conn;

if (!isset($_SESSION)) {
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

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

ob_start();

switch ($controller) {
    case 'auth':
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] == 'register') {
                $authController->register();
            } elseif ($_POST['action'] == 'login') {
                $authController->login();
            }
        } else {
            if ($action == 'register') {
                include dirname(__FILE__) . '/../app/views/auth/register.php';
            } elseif ($action == 'login') {
                include dirname(__FILE__) . '/../app/views/auth/login.php';
            } elseif ($action == 'logout') {
                $authController->logout();
            }
        }
        break;
    case 'quiz':
        if (!isset($_SESSION['username'])) {
            header("Location: /public/index.php?controller=auth&action=login");
            exit();
        }
        $quizController = new QuizController();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] == 'addQuestion' && isAdmin()) {
                $quizController->addQuestion();
            } elseif ($_POST['action'] == 'createQuiz') {
                $quizController->createQuiz();
            } elseif ($_POST['action'] == 'updateQuiz') {
                $quizController->updateQuiz($_GET['quiz_id']);
            } elseif ($_POST['action'] == 'updateQuestion') {
                $quizController->updateQuestion($_GET['question_id']);
            } elseif ($_POST['action'] == 'submitQuiz') {
                $quizController->submitQuiz($_GET['quiz_id']);
            }
        } else {
            if ($action == 'getAllQuizzes') {
                $quizController->getAllQuizzes();
            } elseif ($action == 'takeQuiz') {
                $quizController->takeQuiz($_GET['quiz_id']);
            } elseif ($action == 'summary') {
                $quizController->summary();
            } elseif ($action == 'createQuizForm' && isAdmin()) {
                $quizController->createQuizForm();
            } elseif ($action == 'editQuizForm' && isAdmin()) {
                $quizController->editQuizForm($_GET['quiz_id']);
            } elseif ($action == 'manageQuizzes' && isAdmin()) {
                $quizController->manageQuizzes();
            } elseif ($action == 'addQuestionForm' && isAdmin()) {
                $quizController->addQuestionForm($_GET['quiz_id']);
            } elseif ($action == 'editQuestionForm' && isAdmin()) {
                $quizController->editQuestionForm($_GET['question_id']);
            } elseif ($action == 'deleteQuiz' && isAdmin()) {
                $quizController->deleteQuiz($_GET['quiz_id']);
            } elseif ($action == 'deleteQuestion' && isAdmin()) {
                $quizController->deleteQuestion($_GET['question_id']);
            }
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
    <link href="/public/quiz.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="app"><?php echo $content; ?></div>
</body>
</html>