<?php
session_start();

include '../config/database_connect.php';
include '../classes/Questions.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password,$dbname);

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    echo "A felhasznalo nincs bejelentkezve";
    exit;
}

$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : null;
// Ha a kérdés hozzáadására van szükség
    if (isset($_POST['question_text'])) {
        $question_text = $_POST['question_text'];

        $sql = "INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $quiz_id, $question_text);

        if ($stmt->execute()) {
            echo "Kérdés sikeresen hozzáadva!";
        } else {
            echo "Hiba a kérdés hozzáadása során: " . $stmt->error;
        }
        $stmt->close();
    }



$quiz = new Questions($conn);
    if (isset($_POST['question']) && isset($_POST['answer1']) && isset($_POST['answer2']) && isset($_POST['answer3']) && isset($_POST['correct_answer'])) {
        $question = $_POST['question'];
        $answer1 = $_POST['answer1'];
        $answer2 = $_POST['answer2'];
        $answer3 = $_POST['answer3'];
        $correctAnswer = $_POST['correct_answer']; // Kiválasztott helyes válasz

        $answers = [
            ['text' => $answer1, 'is_correct' => ($correctAnswer == 1) ? 1 : 0],
            ['text' => $answer2, 'is_correct' => ($correctAnswer == 2) ? 1 : 0],
            ['text' => $answer3, 'is_correct' => ($correctAnswer == 3) ? 1 : 0],
        ];
        try {
            // Új kérdés hozzáadása
            $quiz = new Questions($conn);
            $questionId = $quiz->addQuestion($question, $answers, $userId, $quiz_id);  // $userId és $quizId átadása
            echo "A kérdés hozzáadva, kérdés ID: " . $questionId;
        } catch (Exception $e) {
            echo "Hiba: " . $e->getMessage();
        }
    }
$conn->close();
?>

<form method="POST" action="">
    <label for="question">Kérdés:</label>
    <textarea name="question" required></textarea><br>

    <label for="answer">A:</label>
    <input type="text" name="answer1" required><br>

    <label for="answer2">B:</label>
    <input type="text" name="answer2" required><br>

    <label for="answer3">C:</label>
    <input type="text" name="answer3" required><br>

    <label for="correct_answer">Helyes válasz:</label>
    <select name="correct_answer" required>
        <option value="1">A</option>
        <option value="2">B</option>
        <option value="3">C</option>
    </select><br>

    <button type="submit">Kérdés Hozzáadása</button>
</form>
<a href="../public/index.php">Kvizkérdések megtekintése</a><br>
<p></p>
<a href="../public/quiz.php">Kviz kiprobálása</a>
