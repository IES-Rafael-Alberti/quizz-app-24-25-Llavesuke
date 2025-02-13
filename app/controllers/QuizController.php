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

    /**
     * Adds a new question to a quiz.
     */
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

    /**
     * Creates a new quiz.
     */
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

    /**
     * Updates an existing quiz.
     */
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

    /**
     * Updates an existing question.
     */
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

    /**
     * Displays the quiz for the user to take.
     */
    public function takeQuiz($quiz_id) {
        $questions = $this->question->getByQuizId($quiz_id);
        include dirname(__FILE__) . '/../views/quiz/take.php';
    }

    /**
     * Submits the quiz and calculates the score.
     */
    public function submitQuiz($quiz_id) {
        session_start();
        error_log("submitQuiz called with quiz_id: " . $quiz_id);

        $questions = $this->question->getByQuizId($quiz_id);
        if ($questions === false) {
            error_log("No questions found for quiz_id: " . $quiz_id);
            return;
        }

        $score = 0;
        $total_questions = $questions->num_rows;
        error_log("Total questions: " . $total_questions);

        while ($question = $questions->fetch_assoc()) {
            $question_id = $question['question_id'];
            $selected_option = strtoupper($_POST['question_' . $question_id]);
            $correct_option = $question['correct_option'];
            error_log("Question ID: " . $question_id . ", Selected Option: " . $selected_option . ", Correct Option: " . $correct_option);

            if ($selected_option == $correct_option) {
                $score++;
            }
        }

        $percentage = ($score / $total_questions) * 100;
        error_log("Score: " . $score . ", Percentage: " . $percentage);

        // Insert the result into the database
        $user_id = $_SESSION['user_id'];
        $query = "INSERT INTO Resultados (user_id, quiz_id, score, total_questions, percentage) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            error_log("Failed to prepare statement: " . $this->db->error);
            return;
        }
        $stmt->bind_param("iiiii", $user_id, $quiz_id, $score, $total_questions, $percentage);
        if ($stmt->execute()) {
            error_log("Result inserted successfully");
        } else {
            error_log("Failed to insert result: " . $stmt->error);
        }

        header("Location: /public/index.php?controller=quiz&action=summary&score=$score&total_questions=$total_questions&percentage=$percentage&quiz_id=$quiz_id");
        exit();
    }

    /**
     * Retrieves statistics for a quiz.
     */
    public function getStatistics($quiz_id) {
        $query = "SELECT AVG(percentage) as average_score, COUNT(*) as attempts FROM Resultados WHERE quiz_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Displays the summary of the quiz results.
     */
    public function summary() {
        $score = $_GET['score'];
        $total_questions = $_GET['total_questions'];
        $percentage = $_GET['percentage'];
        $quiz_id = $_GET['quiz_id'];
        $statistics = $this->getStatistics($quiz_id);
        include dirname(__FILE__) . '/../views/quiz/summary.php';
    }

    /**
     * Retrieves and displays all quizzes.
     */
    public function getAllQuizzes() {
        $quizzes = $this->quiz->getAll();
        include dirname(__FILE__) . '/../views/quiz/list.php';
    }

    /**
     * Displays the form to edit a quiz.
     */
    public function editQuizForm($quiz_id) {
        $quiz = $this->quiz->getById($quiz_id);
        $questions = $this->question->getByQuizId($quiz_id);
        include dirname(__FILE__) . '/../views/quiz/edit.php';
    }

    /**
     * Deletes a quiz and its associated questions.
     */
    public function deleteQuiz($quiz_id) {
        // Delete associated questions
        $query = "DELETE FROM Preguntas WHERE quiz_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        if (!$stmt->execute()) {
            echo "Error deleting questions";
            return;
        }

        // Delete the quiz
        if ($this->quiz->delete($quiz_id)) {
            header("Location: /public/index.php?controller=quiz&action=manageQuizzes");
            exit();
        } else {
            echo "Error deleting quiz";
        }
    }

    /**
     * Displays the quiz management page.
     */
    public function manageQuizzes() {
        $quizzes = $this->quiz->getAll();
        include dirname(__FILE__) . '/../views/quiz/manage.php';
    }

    /**
     * Displays the form to create a new quiz.
     */
    public function createQuizForm() {
        include dirname(__FILE__) . '/../views/quiz/create.php';
    }

    /**
     * Displays the form to add a new question to a quiz.
     */
    public function addQuestionForm($quiz_id) {
        include dirname(__FILE__) . '/../views/quiz/add_question.php';
    }

    /**
     * Displays the form to edit a question.
     */
    public function editQuestionForm($question_id) {
        $question = $this->question->getById($question_id);
        include dirname(__FILE__) . '/../views/quiz/edit_question.php';
    }

    /**
     * Deletes a question from a quiz.
     */
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