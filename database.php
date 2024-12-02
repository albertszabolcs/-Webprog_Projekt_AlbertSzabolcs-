<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";

$conn = new mysqli($servername, $username, $password,$dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

$sql = "CREATE DATABASE IF NOT EXISTS quiz_app";
if ($conn->query($sql) === TRUE) {
    echo "<br>Database created succesfully";
} else {
    echo  "Error creating database: " . $conn->error;
}

$conn->select_db("quiz_app");

$sql = "CREATE TABLE IF NOT EXISTS users (
     id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
     username VARCHAR(50) NOT NULL,
     password VARCHAR(255) NOT NULL,
     email VARCHAR(100) NOT NULL UNIQUE
)";
if ($conn->query($sql) === TRUE) {
    echo "<br>Table created successfully";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
$sql = "CREATE TABLE IF NOT EXISTS quizzes (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "<br>Table created successfully";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}


$sql = "CREATE TABLE IF NOT EXISTS questions (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "<br>Table created successfully";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
$questions = [
    [1, 'Melyik a világ legmagasabb épülete?'],
    [3, 'Hogyan működik a fotoszintézis?'],
    [4, 'Mi az energia megmaradásának törvénye?'],
    [1, 'Mi az 5G technológia lényege?'],


];
$sql = "CREATE TABLE IF NOT EXISTS answers (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    answer_text TEXT NOT NULL,
    is_correct BOOLEAN NOT NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "<br>Table created successfully";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
echo "<br>";
foreach ($questions as $question) {
    $quizId = $question[0];
    $text = $question[1];

    $sql = "SELECT id FROM questions WHERE question_text = '$text'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        echo "<br>Question created successfully";

    } else {
        $sql = "INSERT INTO questions (quiz_id, question_text) VALUES ($quizId, '$text')";
        if ($conn->query($sql) === TRUE) {
            echo "<br>Question added successfully";
        }else{
            echo "Error adding questions: " . $sql . "<br>" . $conn->error;
        }

    }

}
echo "<br>";
$answers = [
    [1, 'Burj Khalifa', 1],
    [1, 'Empire State Building', 0],
    [1, 'Eiffel-torony', 0],
    [1, 'Shanghai Tower', 0],

    [3, 'A növények oxigént termelnek', 1],
    [3, 'A növények szén-dioxidot termelnek', 0],
    [3, 'A növények lebontják az oxigént', 0],
    [3, 'A növények energiát vesznek fel a talajból', 0],

    [4, 'Az energia nem vész el, csak átalakul', 1],
    [4, 'Az energia mindig csökken', 0],
    [4, 'Az energia folyamatosan nő', 0],
    [4, 'Az energia minden esetben megszűnik', 0],

    [1, 'Gyorsabb adatátvitel és kisebb késleltetés', 1],
    [1, 'Régebbi technológia, mint a 4G', 0],
    [1, 'Csak okostelefonokhoz használható', 0],
    [1, 'WiFi helyett működik', 0],
];
foreach ($answers as $answer) {
    $questionId = $answer[0];
    $answerText = $answer[1];
    $isCorrect = $answer[2];



    if ($result->num_rows > 0) {
        echo "<br>Answer already exists: $answerText (Question ID: $questionId)";
    } else {
         $sql = "INSERT INTO answers (question_id, answer_text, is_correct)
            VALUES ($questionId, '$answerText', $isCorrect)";
    if ($conn->query($sql) === TRUE) {
        echo "Answer added successfully: $answerText<br>";
    } else {
        echo "Error adding answer: " . $conn->error . "<br>";

        }
    }
}
$usersData = [
    ['Peter Imre', password_hash('password123', PASSWORD_DEFAULT), 'peterimre@example.com'],
    ['Kedves Lajos', password_hash('securepass', PASSWORD_DEFAULT), 'kedveslajos@example.com'],
    ['Zsigmond Alpar', password_hash('niki457', PASSWORD_DEFAULT),   'zsigmondalar@example.com'],
    ['Olah Nikolett',password_hash('albert789', PASSWORD_DEFAULT),   'olahnikolett@example.com'],
];

foreach ($usersData as $user) {
    $username = $user[0];
    $password = $user[1];
    $email = $user[2];

    $checkSql = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        echo "<br>User already exists: $username ($email)";
    } else {
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        if ($conn->query($sql) === TRUE) {
            echo "<br>User added successfully: $username";
        } else {
            echo "<br>Error adding user: " . $conn->error. "<br>";
        }
    }
}
echo "<br>";
$quizzes = [
    ['Világ csodái', 'Fedezd fel a világ építészeti csodáit és történetüket.', 1],
    ['Természettudományi érdekességek', 'Kérdések a természet és a tudomány világából.', 3],
    ['Fizikai alapfogalmak', 'Egyszerű, de érdekes kérdések a fizikából.', 4],
    ['Digitális technológiák', 'Mennyire ismered a modern technológiákat?', 1],
];
foreach ($quizzes as $quiz) {
    $title = $quiz[0];
    $description = $quiz[1];
    $created_by = $quiz[2];

    $Sql = "SELECT id FROM quizzes WHERE title = '$title'";
    $result = $conn->query($Sql);

    if ($result->num_rows > 0) {
        echo "Quiz with title '$title' already exists.<br>";
    } else {
        $sql = "INSERT INTO quizzes (title, description, created_by) 
                VALUES ('$title', '$description', $created_by)";
        if ($conn->query($sql) === TRUE) {
            echo "Quiz added successfully: $title<br>";
        } else {
            echo "Error adding quiz: " . $conn->error . "<br>";
        }
    }
}
$conn->close();
?>


