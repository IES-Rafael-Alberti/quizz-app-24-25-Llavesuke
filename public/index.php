<?php
// Start output buffering
ob_start();

// Load configurations and controllers
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../app/controllers/AuthController.php');
require_once(__DIR__ . '/../app/controllers/QuizController.php');

global $conn;

// Start the session if it is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the controller and action from the URL, default to 'auth' and 'login' respectively
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);

// If the user is logged in, do not allow access to login, register, or the root (/)
if ($isLoggedIn && ($controller == 'auth' && ($action == 'login' || $action == 'register') || ($controller == '' && $action == ''))) {
    // Redirect the user to the main page or dashboard
    header("Location: /public/index.php?controller=quiz&action=getAllQuizzes");
    exit();
}

// Handle "Remember Me" logic
if (!$isLoggedIn && isset($_COOKIE['rememberme'])) {
    $token = $_COOKIE['rememberme'];
    $query = "SELECT * FROM Usuarios WHERE remember_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        // Set session variables if the token is valid
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['checked_rememberme'] = true;
        error_log("User authenticated via 'Remember Me'.");
        header("Location: /public/index.php?controller=quiz&action=getAllQuizzes");
        exit();
    } else {
        error_log("Invalid 'rememberme' token.");
    }
}

// Controller routing
switch ($controller) {
    case 'auth':
        $authController = new AuthController();
        if ($action == 'login') {
            $authController->login();
        } elseif ($action == 'register') {
            $authController->register();
        } elseif ($action == 'logout') {
            $authController->logout(); // Only here cookies and session are deleted
        }
        break;
    case 'quiz':
        // Redirect to login if the user is not logged in
        if (!isset($_SESSION['username'])) {
            header("Location: /public/index.php?controller=auth&action=login");
            exit();
        }
        $quizController = new QuizController();
        if ($action == 'getAllQuizzes') {
            $quizController->getAllQuizzes();
        } elseif ($action == 'submitQuiz') {
            $quizController->submitQuiz($_GET['quiz_id']);
        } elseif ($action == 'takeQuiz') {
            $quizController->takeQuiz($_GET['quiz_id']);
        } elseif ($action == 'summary') {
            $quizController->summary();
        } elseif ($action == 'createQuizForm') {
            $quizController->createQuizForm();
        } elseif ($action == 'createQuiz') {
            $quizController->createQuiz();
        } elseif ($action == 'editQuizForm') {
            $quizController->editQuizForm($_GET['quiz_id']);
        } elseif ($action == 'updateQuiz') {
            $quizController->updateQuiz($_GET['quiz_id']);
        } elseif ($action == 'deleteQuiz') {
            $quizController->deleteQuiz($_GET['quiz_id']);
        } elseif ($action == 'manageQuizzes') {
            $quizController->manageQuizzes();
        } elseif ($action == 'addQuestionForm') {
            $quizController->addQuestionForm($_GET['quiz_id']);
        } elseif ($action == 'addQuestion') {
            $quizController->addQuestion();
        } elseif ($action == 'editQuestionForm') {
            $quizController->editQuestionForm($_GET['question_id']);
        } elseif ($action == 'updateQuestion') {
            $quizController->updateQuestion($_GET['question_id']);
        } elseif ($action == 'deleteQuestion') {
            $quizController->deleteQuestion($_GET['question_id']);
        }
        break;
    default:
        echo "Controller not found";
        break;
}

// Get the generated content
$content = ob_get_clean();

// Display the HTML template
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
<header>
    <nav>
        <ul>
            <li><a href="/public/index.php">Home</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="/public/index.php?controller=auth&action=logout">Logout</a></li>
            <?php else: ?>
                <li><a href="/public/index.php?controller=auth&action=login">Login</a></li>
                <li><a href="/public/index.php?controller=auth&action=register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<div id="app"><?php echo $content; ?></div>
</body>
</html>