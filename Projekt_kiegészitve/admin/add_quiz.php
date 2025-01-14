<?php

session_start();
include '../config/database_connect.php';
include '../classes/Quizzes.php';  // Ha szükséges, hívhatod a Quizzes osztályt is.

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ellenőrizzük, hogy a quiz nevet megadták-e
if (isset($_POST['quiz_name'])) {
    $quiz_name = $_POST['quiz_name'];

    // Adat beszúrása a quizzes táblába
    $sql = "INSERT INTO quizzes (quiz_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $quiz_name); // "s" az adat típusára utal (string)

    if ($stmt->execute()) {
        $quiz_id = $conn->insert_id;
        echo "Új quiz sikeresen hozzáadva!";
        header("Location: add_question.php?quiz_id=" . $quiz_id);
        exit();
    } else {
        echo "Hiba a quiz hozzáadása során: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!-- Form, amellyel új quiz hozzáadható -->
<form method="POST" action="">
    <label for="quiz_name">Új quiz neve:</label>
    <input type="text" name="quiz_name" required><br>

    <button type="submit">Quiz hozzáadása</button>
</form>
