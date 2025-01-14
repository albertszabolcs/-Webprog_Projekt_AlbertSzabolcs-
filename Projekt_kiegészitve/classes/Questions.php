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
    public function addQuestion($questionText, $answers,$userId,$quizId)
    {
        if ($quizId == null || $userId == null) {
            throw new Exception("Quiz ID or User ID cannot be null");
        }
        $stmt = $this->conn->prepare("INSERT INTO questions (question_text,user_id,quiz_id) VALUES (?,?,?)");
        $stmt->bind_param("sii", $questionText, $userId, $quizId);
        $stmt->execute();
        $questionId = $stmt->insert_id;
        $stmt->close();

        // Válaszok hozzáadása
        foreach ($answers as $answer) {
            $stmt = $this->conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $questionId, $answer['text'], $answer['is_correct']);
            $stmt->execute();
        }

        $stmt->close();
        return $questionId;
    }
    public function getAllQuestions() {
        $sql = "SELECT question_id, question_text FROM questions";
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
        $sql = "SELECT answer_text FROM answers WHERE question_id = ?";
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
