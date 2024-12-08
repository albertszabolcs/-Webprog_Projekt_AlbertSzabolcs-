<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_app";


$conn = new mysqli($servername, $username, $password);

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
//Táblák létrehozása

//Felhasználók tábla
$sql = "CREATE TABLE IF NOT EXISTS users (
     user_id INT AUTO_INCREMENT PRIMARY KEY,
     username VARCHAR(50) NOT NULL,
     password_hash VARCHAR(255) NOT NULL,
     email VARCHAR(100) NOT NULL UNIQUE
)";
if ($conn->query($sql) === TRUE) {
    echo "<br>Table created successfully";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
//Kérdések tábla
$sql = "CREATE TABLE IF NOT EXISTS questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    answer TEXT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id), 
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
)";
if ($conn->query($sql) === TRUE) {
    echo "<br>Table created successfully";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

//Válaszok tábla
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
$conn->close();
?>


