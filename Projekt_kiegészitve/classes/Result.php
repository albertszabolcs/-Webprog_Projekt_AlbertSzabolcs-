<?php

class Result {
    private $conn;

    // Konstruktor: Adatbázis kapcsolat
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Eredmény hozzáadása
    public function addResult($user_id, $quiz_id, $score) {
        $sql = "INSERT INTO results (user_id, quiz_id, score) VALUES (?, ?, ?)";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("iii", $user_id, $quiz_id, $score); // "iii" - 3 integer paraméter
            if ($stmt->execute()) {
                echo "Result added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    // Eredmények lekérdezése egy felhasználó számára
    public function getResultsByUser($user_id) {
        $sql = "SELECT * FROM results WHERE user_id = ?";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id); // "i" - integer paraméter
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Result ID: " . $row["result_id"] . " - Quiz ID: " . $row["quiz_id"] . " - Score: " . $row["score"] . "<br>";
                }
            } else {
                echo "No results found for this user.";
            }
            $stmt->close();
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    // Eredmény frissítése
    public function updateResult($result_id, $new_score) {
        $sql = "UPDATE results SET score = ? WHERE result_id = ?";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("ii", $new_score, $result_id); // "ii" - integer paraméterek
            if ($stmt->execute()) {
                echo "Result updated successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    // Eredmény törlése
    public function deleteResult($result_id) {
        $sql = "DELETE FROM results WHERE result_id = ?";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $result_id); // "i" - integer paraméter
            if ($stmt->execute()) {
                echo "Result deleted successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    // Eredmények lekérdezése kvíz alapján
    public function getResultsByQuiz($quiz_id) {
        $sql = "SELECT * FROM results WHERE quiz_id = ?";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $quiz_id); // "i" - integer paraméter
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Result ID: " . $row["result_id"] . " - User ID: " . $row["user_id"] . " - Score: " . $row["score"] . "<br>";
                }
            } else {
                echo "No results found for this quiz.";
            }
            $stmt->close();
        } else {
            echo "Error: " . $this->conn->error;
        }
    }
}
?>

