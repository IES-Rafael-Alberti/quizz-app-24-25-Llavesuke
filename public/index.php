<?php
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../app/controllers/AuthController.php');
require_once(__DIR__ . '/../app/controllers/QuizController.php');

session_start();

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

ob_start(); // Start output buffering

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
                include __DIR__ . '/../app/views/auth/register.php';
            } else {
                include __DIR__ . '/../app/views/auth/login.php';
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
            } elseif ($_POST['action'] == 'createQuiz' && isAdmin()) {
                $quizController->createQuiz();
            } elseif ($_POST['action'] == 'updateQuiz' && isAdmin()) {
                $quizController->updateQuiz($_GET['quiz_id']);
            } elseif ($_POST['action'] == 'deleteQuiz' && isAdmin()) {
                $quizController->deleteQuiz($_GET['quiz_id']);
            } elseif ($_POST['action'] == 'updateQuestion' && isAdmin()) {
                $quizController->updateQuestion($_GET['question_id']);
            }
        } else {
            if ($action == 'getAllQuizzes') {
                $quizController->getAllQuizzes();
            } elseif ($action == 'takeQuiz') {
                $quizController->takeQuiz(isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null);
            } elseif ($action == 'submitQuiz') {
                $quizController->submitQuiz(isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null);
            } elseif ($action == 'summary') {
                $quizController->summary();
            } elseif ($action == 'manageQuizzes' && isAdmin()) {
                $quizController->manageQuizzes();
            } elseif ($action == 'createQuizForm' && isAdmin()) {
                $quizController->createQuizForm();
            } elseif ($action == 'addQuestionForm' && isAdmin()) {
                $quizController->addQuestionForm($_GET['quiz_id']);
            } elseif ($action == 'editQuestionForm' && isAdmin()) {
                $quizController->editQuestionForm($_GET['question_id']);
            } elseif ($action == 'deleteQuestion' && isAdmin()) {
                $quizController->deleteQuestion($_GET['question_id']);
            } elseif ($action == 'editQuizForm' && isAdmin()) {
                $quizController->editQuizForm($_GET['quiz_id']);
            } elseif ($action == 'getStatistics') {
                $quizController->getStatistics($_GET['quiz_id']);
            } else {
                echo "Action not found";
            }
        }
        break;
    default:
        echo "Controller not found";
        break;
}

ob_end_flush(); // End output buffering and flush the output
?>
