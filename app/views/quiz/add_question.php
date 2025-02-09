<form method="POST" action="/public/index.php?controller=quiz&action=addQuestion">
    $controller = $_GET['controller'];
    $action = $_GET['action'];

    switch ($controller) {
    case 'auth':
    $authController = new AuthController();
    $authController->$action();
    break;
    case 'quiz':
    $quizController = new QuizController();
    $quizController->$action($_GET['quiz_id'] ?? null);
    break;
    default:
    echo "Controlador no encontrado";
    break;
    }
    ?>