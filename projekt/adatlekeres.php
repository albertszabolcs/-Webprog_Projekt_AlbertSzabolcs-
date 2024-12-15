<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password, $dbname);

// Adatok lekérése a kérdések táblából
$sql = "SELECT * FROM questions";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Adatok megjelenítése
    while($row = $result->fetch_assoc()) {
        echo "<p>Kérdés: " . $row["question_text"] . "</p>";
        echo "<p>Válasz: " . $row["answer"] . "</p>";
    }
} else {
    echo "Nincs adat.";
}

$conn->close();
?>
