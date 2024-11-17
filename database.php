<?php

$servername = "localhost";
$username = "root";
$password = "";

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
$sql = "CREATE TABLE IF NOT EXISTS users (
     id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
     username VARCHAR(50) NOT NULL,
     password VARCHAR(255) NOT NULL,
     email VARCHAR(100) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "<br>Table created successfully";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}
$sql = "CREATE TABLE IF NOT EXISTS quizzes (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
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
$conn->close();
?>

