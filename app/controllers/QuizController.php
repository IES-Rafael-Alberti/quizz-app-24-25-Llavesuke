<?php
require_once dirname(__FILE__) . '/../../config/config.php';
require_once dirname(__FILE__) . '/../models/Quiz.php';
require_once dirname(__FILE__) . '/../models/Question.php';

class QuizController {
    private $db;
    private $quiz;
    private $question;

    public function __construct() {
        global $conn;
        $this->db = $conn;
        $this->quiz = new Quiz($this->db);
        $this->question = new Question($this->db);
    }

    public function addQuestion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->question->quiz_id = $_POST['quiz_id'];
            $this->question->question_text = $_POST['question_text'];
            $this->question->option_a = $_POST['option_a'];
            $this->question->option_b = $_POST['option_b'];
            $this->question->option_c = $_POST['option_c'];
            $this->question->option_d = $_POST['option_d'];
            $this->question->correct_option = $_POST['correct_option'];
            if ($this->question->add()) {
                header("Location: /public/index.php?controller=quiz&action=editQuizForm&quiz_id=" . $_POST['quiz_id']);
                exit();
            } else {
                echo "Error adding question";
            }
        }
    }

    public function createQuiz() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->quiz->title = $_POST['title'];
            $this->quiz->description = $_POST['description'];
            if ($this->quiz->create()) {
                header("Location: /public/index.php?controller=quiz&action=manageQuizzes");
                exit();
            } else {
                echo "Error creating quiz";
            }
        }
    }

    public function updateQuiz($quiz_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->quiz->quiz_id = $quiz_id;
            $this->quiz->title = $_POST['title'];
            $this->quiz->description = $_POST['description'];
            if ($this->quiz->update()) {
                header("Location: /public/index.php?controller=quiz&action=manageQuizzes");
                exit();
            } else {
                echo "Error updating quiz";
            }
        }
    }

    public function updateQuestion($question_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->question->question_id = $question_id;
            $this->question->question_text = $_POST['question_text'];
            $this->question->option_a = $_POST['option_a'];
            $this->question->option_b = $_POST['option_b'];
            $this->question->option_c = $_POST['option_c'];
            $this->question->option_d = $_POST['option_d'];
            $this->question->correct_option = $_POST['correct_option'];
            if ($this->question->update()) {
                header("Location: /public/index.php?controller=quiz&action=editQuizForm&quiz_id=" . $_POST['quiz_id']);
                exit();
            } else {
                echo "Error updating question";
            }
        }
    }

    public function takeQuiz($quiz_id) {
        $questions = $this->question->getByQuizId($quiz_id);
        include dirname(__FILE__) . '/../views/quiz/take.php';
    }

    public function submitQuiz($quiz_id) {
        $questions = $this->question->getByQuizId($quiz_id);
        $score = 0;
        $total_questions = $questions->num_rows;

        while ($question = $questions->fetch_assoc()) {
            $selected_option = $_POST['question_' . $question['question_id']];
            if ($selected_option == $question['correct_option_text']) {
                $score++;
            }
        }

        $percentage = ($score / $total_questions) * 100;

        // Insert the result into the database
        $user_id = $_SESSION['user_id'];
        $query = "INSERT INTO Resultados (user_id, quiz_id, score, total_questions, percentage) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiiii", $user_id, $quiz_id, $score, $total_questions, $percentage);
        $stmt->execute();

        header("Location: /public/index.php?controller=quiz&action=summary&score=$score&total_questions=$total_questions&percentage=$percentage&quiz_id=$quiz_id");
        exit();
    }

    public function getStatistics($quiz_id) {
        $query = "SELECT AVG(percentage) as average_score, COUNT(*) as attempts FROM Resultados WHERE quiz_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function summary() {
        $score = $_GET['score'];
        $total_questions = $_GET['total_questions'];
        $percentage = $_GET['percentage'];
        $quiz_id = $_GET['quiz_id'];
        $statistics = $this->getStatistics($quiz_id);
        include dirname(__FILE__) . '/../views/quiz/summary.php';
    }

    public function getAllQuizzes() {
        $quizzes = $this->quiz->getAll();
        include dirname(__FILE__) . '/../views/quiz/list.php';
    }

    public function editQuizForm($quiz_id) {
        $quiz = $this->quiz->getById($quiz_id);
        $questions = $this->question->getByQuizId($quiz_id);
        include dirname(__FILE__) . '/../views/quiz/edit.php';
    }

    public function deleteQuiz($quiz_id) {
        if ($this->quiz->delete($quiz_id)) {
            header("Location: /public/index.php?controller=quiz&action=manageQuizzes");
            exit();
        } else {
            echo "Error deleting quiz";
        }
    }

    public function manageQuizzes() {
        $quizzes = $this->quiz->getAll();
        include dirname(__FILE__) . '/../views/quiz/manage.php';
    }

    public function createQuizForm() {
        include dirname(__FILE__) . '/../views/quiz/create.php';
    }

    public function addQuestionForm($quiz_id) {
        include dirname(__FILE__) . '/../views/quiz/add_question.php';
    }

    public function editQuestionForm($question_id) {
        $question = $this->question->getById($question_id);
        include dirname(__FILE__) . '/../views/quiz/edit_question.php';
    }

    public function deleteQuestion($question_id) {
        $quiz_id = $this->question->getQuizIdByQuestionId($question_id);
        if ($this->question->delete($question_id)) {
            header("Location: /public/index.php?controller=quiz&action=editQuizForm&quiz_id=" . $quiz_id);
            exit();
        } else {
            echo "Error deleting question";
        }
    }
}
?>