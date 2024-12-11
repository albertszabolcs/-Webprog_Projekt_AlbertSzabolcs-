<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

// Adatbázis-kapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Hiba az adatbázis-kapcsolat létrehozásakor: " . $conn->connect_error);
}

// Eredmény számoló változó
$correctAnswers = 0;
$totalQuestions = 0;

// Válaszok feldolgozása
foreach ($_POST as $question => $answer_id) {
    // Csak a kérdésekhez tartozó válaszokat dolgozzuk fel
    if (strpos($question, 'question_') === 0) {
        $question_id = str_replace('question_', '', $question);

        // Válasz ellenőrzése
        $sql = "SELECT * FROM answers WHERE id = ? AND question_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $answer_id, $question_id);
        $stmt->execute();
        $answer_result = $stmt->get_result();

        // Ha van találat és a válasz helyes, növeljük a helyes válaszok számát
        if ($answer_result->num_rows > 0) {
            $answer_row = $answer_result->fetch_assoc();
            if ($answer_row['is_correct'] == 1) {
                $correctAnswers++;
            }
        }
        $totalQuestions++;
    }
}

// Eredmény megjelenítése
echo "<h2>Értékelés:</h2>";
echo "<p>Összes kérdés: $totalQuestions</p>";
echo "<p>Helyes válaszok: $correctAnswers</p>";

// Ha a válaszok teljesen helyesek
if ($correctAnswers == $totalQuestions) {
    echo "<p>Gratulálunk! Minden kérdésre helyesen válaszoltál.</p>";
} else {
    echo "<p>Próbáld meg újra, és javítsd a válaszaidat!</p>";
}

// Kapcsolat lezárása
$conn->close();
?>
<a href="logout.php">Kilépés a kvizből</a>


