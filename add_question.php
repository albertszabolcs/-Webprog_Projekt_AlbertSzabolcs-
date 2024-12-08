<?php

include 'database_connect.php';
include 'Questions.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";


$conn = new mysqli($servername, $username, $password);


$quiz = new Questions($conn);

if (isset($_POST['question']) && isset($_POST['answer'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // Új kérdés hozzáadása
    $questionId = $quiz->addQuestion($question, $answer);
    echo "A kérdés hozzáadva, kérdés ID: " . $questionId;
}
?>

<form method="POST" action="">
    <label for="question">Kérdés:</label>
    <textarea name="question" required></textarea><br>

    <label for="answer">Válasz:</label>
    <input type="text" name="answer" required><br>

    <button type="submit">Kérdés hozzáadása</button>
</form>
