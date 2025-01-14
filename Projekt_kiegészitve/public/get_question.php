<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

// Csatlakozás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Csatlakozási hiba ellenőrzés
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Adatok lekérése
$sql = "SELECT * FROM questions";
$result = $conn->query($sql);

$questions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}
// JSON válasz visszaadása

echo json_encode($questions,JSON_UNESCAPED_UNICODE);

// Kapcsolat lezárása
$conn->close();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Kérdések</title>
</head>
<body>
<div id="questions"></div>

<script>
    fetch('get_question.php') // A PHP fájl elérési útja
        .then(response => response.json())
        .then(data => {
            const questionsDiv = document.getElementById('questions');
            data.forEach(question => {
                const questionElement = document.createElement('p');
                questionElement.textContent = question.question_text;
                questionsDiv.appendChild(questionElement);
            });
        })
        .catch(error => console.error('Error fetching questions:', error));
</script>
</body>
</html>

