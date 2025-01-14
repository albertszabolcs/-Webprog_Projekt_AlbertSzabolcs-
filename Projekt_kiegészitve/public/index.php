<?php

// Munkamenet indítása
session_start();
include '../config/database_connect.php';
include '../classes/Questions.php';

// Adatbázis kapcsolat létrehozása
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolati hiba ellenőrzése
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// Questions osztály példányosítása
$quiz = new Questions($conn);

// Kérdések lekérdezése
$questions = $quiz->getAllQuestions();

// Felhasználónevek és kérdések lekérdezése JOIN segítségével
$sql = "SELECT questions.question_id AS question_id, 
               questions.question_text AS question_text, 
               answers.answer_text AS answer,
               users.username
        FROM questions 
        JOIN users ON questions.user_id = users.user_id
        LEFT JOIN answers ON questions.question_id = answers.question_id
        WHERE answers.is_correct = 1";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kvízkérdések</title>
</head>
<body>
<h1>Elérhető kérdések</h1>

<?php
// Ellenőrizzük, hogy vannak-e kérdések az adatbázisban
if ($result->num_rows > 0):
    // Kérdések listázása
    while ($row = $result->fetch_assoc()): ?>
        <div>
            <strong>Kérdés:</strong> <?= htmlspecialchars($row['question_text']) ?><br>
            <strong>Helyes válasz:</strong> <?= htmlspecialchars($row['answer']) ?><br>
            <em>Kérdező:</em> <?= htmlspecialchars($row['username']) ?>
        </div>
        <hr>
    <?php endwhile;
else: ?>
    <p>Jelenleg nincsenek kérdések.</p>
<?php endif; ?>

<h2>Összes kérdés táblázatos formában</h2>

<?php if (!empty($questions)): ?>
    <table border="1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Kérdés</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($questions as $question ): ?>
            <tr>
                <td><?= htmlspecialchars($question['question_id']) ?></td>
                <td><?= htmlspecialchars($question['question_text']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nincs elérhető kérdés az adatbázisban.</p>
<?php endif; ?>

<a href="../admin/update_question.php">Kérdés Frissitése</a>
<br>
<a href="../admin/delete_question.php">Kérdés Törlése</a>
</body>
</html>

<?php
// Adatbázis kapcsolat lezárása
$conn->close();
?>
