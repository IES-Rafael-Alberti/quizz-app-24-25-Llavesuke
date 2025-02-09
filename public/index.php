<?php
require_once(__DIR__ . '/../config/config.php');
require_once (__DIR__ . '/../app/controllers/AuthController.php');
require_once (__DIR__ . '/../app/controllers/QuizController.php');

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

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
        $quizController = new QuizController();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] == 'addQuestion') {
                $quizController->addQuestion();
            }
        } else {
            $quizController->$action(isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null);
        }
        break;
    default:
        echo "Controller not found";
        break;
}
?>