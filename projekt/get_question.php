<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password, $dbname);

// Adatok lekérése
$sql = "SELECT * FROM questions";
$result = $conn->query($sql);

$questions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}

echo json_encode($questions); // JSON formátumban küldjük vissza az adatokat
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Adatok Lekérése</title>
    <script>
        function loadQuestions() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "get_questions.php", true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const questions = JSON.parse(xhr.responseText);
                    const output = document.getElementById("questions");
                    output.innerHTML = ""; // Előző tartalom törlése

                    if (questions.length === 0) {
                        output.innerHTML = "<p>Nincs kérdés a rendszerben.</p>";
                    } else {
                        questions.forEach(function(question) {
                            const p = document.createElement("p");
                            p.innerHTML = `Kérdés: ${question.question_text} <br> Válasz: ${question.answer}`;
                            output.appendChild(p);
                        });
                    }
                } else {
                    console.error("Hiba történt az adatok betöltésekor.");
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
<h1>Kérdések</h1>
<div id="questions"></div>
<button onclick="loadQuestions()">Kérdések betöltése</button>
</body>
</html>