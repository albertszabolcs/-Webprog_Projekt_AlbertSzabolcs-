<?php
session_start();
include 'database_connect.php';
include 'Questions.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password, $dbname);

$quiz = new Questions($conn);

// Ellenőrizzük, hogy van-e kérdés és válasz, amit frissíteni szeretnénk
if (isset($_POST['question_id']) && isset($_POST['question']) && isset($_POST['answer'])) {
    $question_id = $_POST['question_id'];
    $question_text = $_POST['question'];
    $answer = $_POST['answer'];

    // Frissítjük a kérdést és választ
    $sql = "UPDATE questions SET question_text = ?, answer = ? WHERE question_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $question_text, $answer, $question_id); // i = integer, s = string
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "A kérdés sikeresen frissítve!";
    } else {
        echo "Nincs változtatás, vagy a kérdés nem létezik!";
    }

    $stmt->close();
}

$conn->close();
?>

<!-- Form, amely lehetővé teszi a kérdés és válasz frissítését -->
<form method="POST" action="">
    <label for="question_id">Kérdés ID:</label>
    <input type="number" name="question_id" required><br>

    <label for="question">Új kérdés:</label>
    <textarea name="question" required></textarea><br>

    <label for="answer">Új válasz:</label>
    <input type="text" name="answer" required><br>

    <button type="submit">Kérdés frissítése</button>
</form>
