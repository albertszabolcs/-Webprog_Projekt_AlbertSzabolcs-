<?php

session_start();
include '../config/database_connect.php';
include '../classes/Questions.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password, $dbname);

$quiz = new Questions($conn);

// Ellenőrizzük, hogy van-e kérdés ID, amit törölni szeretnénk
if (isset($_POST['question_id'])) {
    $question_id = $_POST['question_id'];

    // Az SQL DELETE lekérdezés a válaszok törlésére
    $sql = "DELETE FROM answers WHERE question_id = ?";

    // Lekérdezés előkészítése
    $stmt = $conn->prepare($sql);

    // Paraméterek hozzárendelése (i = integer)
    $stmt->bind_param("i", $question_id);

    // Lekérdezés végrehajtása
    $stmt->execute();
    $stmt->close();

    // Most töröljük a kérdést
    $sql = "DELETE FROM questions WHERE question_id = ?";

    // Lekérdezés előkészítése
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);

    // Lekérdezés végrehajtása
    $stmt->execute();

    // Ellenőrizzük, hogy történt-e törlés
    if ($stmt->affected_rows > 0) {
        echo "A kérdés és a hozzá tartozó válaszok sikeresen törölve!";
    } else {
        echo "Nincs változtatás, vagy a kérdés nem létezik!";
    }

    // Lekérdezés lezárása
    $stmt->close();
}

// Kapcsolat bezárása
$conn->close();
?>

<!-- Form, amely lehetővé teszi a kérdés törlését -->
<form method="POST" action="">
    <label for="question_id">Kérdés ID:</label>
    <input type="number" name="question_id" required><br>

    <button type="submit">Kérdés törlése</button>
</form>

