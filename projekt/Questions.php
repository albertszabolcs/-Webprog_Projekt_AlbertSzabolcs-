<?php
class Questions
{
    private $conn;

    // A konstruktor, amely paraméterként kap egy adatbázis kapcsolatot
    public function __construct($db)
    {
        $this->conn = $db;
        $this->conn->select_db('quiz_app');
    }

    // Kvíz kérdés hozzáadása
    public function addQuestion($question, $answer)
    {
        $questionId = null;
        $question = trim($question);
        $answer = trim($answer);

        // Megnézi, hogy létezik-e már ez a kérdés a táblában
        $sql = "SELECT question_id FROM questions WHERE question_text = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $question);
        $stmt->execute();
        $stmt->bind_result($questionId);

        // Ha már létezik, visszatéríti az id-t
        if ($stmt->fetch()) {
            $stmt->close();
            return $questionId;
        }
        // Ha nem létezik, beszúrja a táblába és visszatéríti az id-t
        $stmt->close();
        // Ha be van jelentkezve a felhasználó, lekérjük az user_id-t
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

        $sql = "INSERT INTO questions (quiz_id,question_text, answer,user_id) VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $quiz_id = 1;
        $stmt->bind_param("issi", $quiz_id, $question, $answer, $user_id);
        $stmt->execute();
        $questionId = $stmt->insert_id;
        $stmt->close();

        return $questionId;
    }
    public function getAllQuestions() {
        $sql = "SELECT question_id, question_text, answer FROM questions";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC); // Adatok tömbként való visszaadása
        } else {
            return []; // Ha nincs adat, üres tömböt ad vissza
        }
    }

    // Kérdések lekérdezése
    public function getQuestions()
    {
        // Lekéri az összes kérdést az adatbázisból
        $sql = "SELECT question_id, question_text FROM questions";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $questions = [];
        while ($row = $result->fetch_assoc()) {
            // Kérdések hozzáadása a tömbhöz
            $questions[] = [
                'question_id' => $row['question_id'],
                'question' => $row['question_text']
            ];
        }

        $stmt->close();
        // Visszatéríti a kérdések tömbjét
        return $questions;
    }

    // Kérdés megválaszolása
    public function checkAnswer($questionId, $userAnswer)
    {
        // A megadott kérdés válaszát ellenőrzi
        $sql = "SELECT answer FROM questions WHERE question_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $questionId);
        $stmt->execute();
        $correctAnswer = "";
        $stmt->bind_result($correctAnswer);

        if ($stmt->fetch()) {
            // Ha a felhasználó válasza helyes, igazat ad vissza
            if (strtolower(trim($userAnswer)) == strtolower(trim($correctAnswer))) {
                $stmt->close();
                return ['isCorrect' => true, 'correctAnswer' => $correctAnswer];
            } else {
                $stmt->close();
                return ['isCorrect' => false, 'correctAnswer' => $correctAnswer];
            }
        }

        $stmt->close();
        return ['isCorrect' => false, 'correctAnswer' => ''];
    }
}
?>
