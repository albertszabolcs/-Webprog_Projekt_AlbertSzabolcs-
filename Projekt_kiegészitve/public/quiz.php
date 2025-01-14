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

$quiz_id = 1;

// Kvíz címe
echo "<h1>Kvíz kitöltése</h1>";
echo "<form method='POST' action='result.php'>";

echo "<input type='hidden' name='quiz_id' value='" . htmlspecialchars($quiz_id) . "'>";


// Kérdések lekérése
$sql = "SELECT * FROM questions";
$result = $conn->query($sql);
if (!$result) {
    die("Hiba a kérdések lekérésekor: " . $conn->error);
}

// Kérdések és válaszok feldolgozása
while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<strong>" . htmlspecialchars($row['question_text']) . "</strong><br>"; // Kérdés szövege

    // Válaszlehetőségek lekérése az adott kérdéshez
    $answers_sql = "SELECT * FROM answers WHERE question_id = ?";
    $stmt = $conn->prepare($answers_sql);
    $stmt->bind_param("i", $row['question_id']);
    $stmt->execute();
    $answers_result = $stmt->get_result();

    // Válaszok kiírása
    while ($answer_row = $answers_result->fetch_assoc()) {
        echo "<input type='radio' name='answers[" . $row['question_id'] . "]' value='" . htmlspecialchars($answer_row['id']) . "'> ";
        echo htmlspecialchars($answer_row['answer_text']) . "<br>";
    }

    echo "</div><br>";
}

// Beküldő gomb
echo "<input type='submit' value='Kvíz beküldése'>";
echo "</form>";

// Kapcsolat lezárása
$conn->close();
?>