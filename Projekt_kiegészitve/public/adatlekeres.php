<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password, $dbname);

// Adatok lekérése a kérdések és válaszok táblájából
$sql = "
    SELECT questions.question_text, answers.answer_text, questions.question_id
    FROM questions
    LEFT JOIN answers ON questions.question_id = answers.question_id
    ORDER BY questions.question_id, answers.answer_text
";
$result = $conn->query($sql);

$questions = []; // Tároljuk a kérdéseket és válaszokat egy tömbben

if ($result->num_rows > 0) {
    // Adatok összegyűjtése
    while($row = $result->fetch_assoc()) {
        $question_id = $row["question_id"];
        $question_text = $row["question_text"];
        $answer_text = $row["answer_text"];

        // Ha még nincs kérdés a tömbben, adjuk hozzá
        if (!isset($questions[$question_id])) {
            $questions[$question_id] = [
                'question_text' => $question_text,
                'answers' => []
            ];
        }

        // A válaszokat hozzáadjuk a kérdéshez
        if ($answer_text) {
            $questions[$question_id]['answers'][] = $answer_text;
        }
    }

    // Kérdések és válaszok megjelenítése
    foreach ($questions as $question_id => $question_data) {
        echo "<p>Kérdés: " . $question_data['question_text'] . "</p>";

        if (!empty($question_data['answers'])) {
            echo "<p>Válaszok:</p>";
            foreach ($question_data['answers'] as $answer) {
                echo "<p>- " . $answer . "</p>";
            }
        } else {
            echo "<p>Válasz: Nincs elérhető válasz.</p>";
        }
    }
} else {
    echo "Nincs adat.";
}

$conn->close();
?>