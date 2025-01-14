<?php

class Quizzes{
    private $conn;
    private $quiz_id;
    private $quiz_name;

    /**
     * @param $conn
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function addQuiz($quiz_name) {
        $sql = "INSERT INTO quizzes (quiz_name) VALUES ('$quiz_name')";

        if ($this->conn->query($sql) === TRUE) {
            echo "New quiz created successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }
    public function getAllQuizzes() {
        $sql = "SELECT * FROM quizzes";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "Quiz ID: " . $row["quiz_id"] . " - Name: " . $row["quiz_name"] . "<br>";
            }
        } else {
            echo "No quizzes found.";
        }
    }

    // Kvíz frissítése
    public function updateQuiz($quiz_id, $new_name) {
        $sql = "UPDATE quizzes SET quiz_name = '$new_name' WHERE quiz_id = $quiz_id";

        if ($this->conn->query($sql) === TRUE) {
            echo "Quiz updated successfully!";
        } else {
            echo "Error: " . $this->conn->error;
        }
    }

    // Kvíz törlése
    public function deleteQuiz($quiz_id) {
        $sql = "DELETE FROM quizzes WHERE quiz_id = $quiz_id";

        if ($this->conn->query($sql) === TRUE) {
            echo "Quiz deleted successfully!";
        } else {
            echo "Error: " . $this->conn->error;
        }
    }
}
?>

