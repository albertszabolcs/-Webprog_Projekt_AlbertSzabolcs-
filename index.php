<?php

session_start();
include 'database_connect.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";


$conn = new mysqli($servername, $username, $password,$dbname);


$sql = "SELECT questions.question_id, questions.question_text, questions.answer, users.username 
        FROM questions 
        JOIN users ON questions.user_id = users.user_id";
$result = $conn->query($sql);

echo "<h1>Elérhető kérdések</h1>";

if ($result->num_rows > 0) {
    // Kérdések listázása
    while ($row = $result->fetch_assoc()) {
        echo "<div><strong>Kérdés: </strong>" . htmlspecialchars($row['question_text']) . "<br>";
        echo "<strong>Helyes válasz: </strong>" . htmlspecialchars($row['answer']) . "<br>";
        echo "<em>Kérdező: </em>" . htmlspecialchars($row['username']) . "</div><hr>";
    }
} else {
    echo "Jelenleg nincsenek kérdések.";
}
?>
