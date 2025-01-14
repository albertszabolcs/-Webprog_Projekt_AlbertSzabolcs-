<?php
session_start();
include '../config/database_connect.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $question_id = $_POST['question_id'];
    $question_text = $_POST['question'];
    $answer1 = $_POST['answer1'];
    $answer2 = $_POST['answer2'];
    $answer3 = $_POST['answer3'];

    $answers = array($answer1, $answer2, $answer3);
    if (count($answers) !== count(array_unique($answers))) {
        echo "A válaszoknak egyedinek kell lenniük!";
    } else {
        // Kérdés frissítése
        $sql = "UPDATE questions SET question_text = ? WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Hiba: " . $conn->error);
        }
        $stmt->bind_param("si", $question_text, $question_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Kérdés frissítve.<br>";
        } else {
            echo "Nem történt változás a kérdés frissítésében.<br>";
        }

        // Régi válaszok törlése
        $sql_delete = "DELETE FROM answers WHERE question_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $question_id);
        $stmt_delete->execute();

        if ($stmt_delete->affected_rows > 0) {
            echo "Régi válaszok törölve.<br>";
        } else {
            echo "Nem sikerült törölni a régi válaszokat.<br>";
        }

        // Új válaszok beszúrása
        $sql_insert = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        foreach ($answers as $index => $answer) {
            $is_correct = ($index == 0) ? 1 : 0;
            $stmt_insert->bind_param("isi", $question_id, $answer, $is_correct);
            $stmt_insert->execute();
        }
        echo "Új válaszok hozzáadva.";
    }
}
?>

<form method="POST" action="">
    <label for="question_id">Kérdés ID:</label><br>
    <input type="number" name="question_id" id="question_id" required><br><br>

    <label for="question">Új kérdés:</label><br>
    <textarea name="question" id="question" rows="3" required></textarea><br><br>

    <label for="answer1">A válasz:</label><br>
    <input type="text" name="answer1" id="answer1" required><br><br>

    <label for="answer2">B válasz:</label><br>
    <input type="text" name="answer2" id="answer2" required><br><br>

    <label for="answer3">C válasz:</label><br>
    <input type="text" name="answer3" id="answer3" required><br><br>

    <button type="submit" name="submit">Kérdés és válasz frissítése</button>
</form>
