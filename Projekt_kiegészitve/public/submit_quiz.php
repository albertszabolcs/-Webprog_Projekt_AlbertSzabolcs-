<?php

session_start();
include '../config/database_connect.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";


$conn = new mysqli($servername, $username, $password,$dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['quiz_id']) || empty($_POST['quiz_id'])) {
        die("Hiányzik a kvíz azonosítója!");
    }
    if (!isset($_POST['answers']) || empty($_POST['answers'])) {
        die("Hiányzó válaszok a kvízhez!");
    }
    if (!isset($_SESSION['user_id'])) {
        die("Hiányzik a bejelentkezett felhasználó azonosítója!");
    }

    $user_id = $_SESSION['user_id']; // Ha már be van jelentkezve
    $quiz_id = $_POST['quiz_id'];  // A kvíz ID-t a formból nyerheted
    $answers = $_POST['answers'];
    $correct_answers = 0;

    foreach ($answers as $question_id => $answer_id) {
    // Ellenőrizzük, hogy a válasz helyes-e
    $sql = "SELECT is_correct FROM answers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $answer_id);
    $stmt->execute();
    $stmt->bind_result($is_correct);
    $stmt->fetch();
    $stmt->close();

    // Ha a válasz helyes, növeljük a helyes válaszok számát
    if ($is_correct) {
        $correct_answers++;
    }
}
    $score = $correct_answers;

    echo "<h2>Kvíz eredmény</h2>";
    echo "<p>Helyes válaszok száma: " . $correct_answers . "</p>";
    echo "<p>Összes válasz: " . count($answers) . "</p>";
    echo "<p>Összpontszám: " . $score . "</p>";

    // Az eredmény hozzáadása a 'results' táblába
    $sql = "INSERT INTO results (user_id, quiz_id, score) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $quiz_id, $score);

    if ($stmt->execute()) {
        echo "A kvíz eredménye sikeresen rögzítve!";
    } else {
        echo "Hiba történt az eredmény rögzítése során: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
$quiz_id = 1;
?>


