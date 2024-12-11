<?php

session_start();
include 'database_connect.php';
include 'User.php';

if (!file_exists('database_connect.php')) {
    die("The database connection file is missing!");
}
if (!isset($conn)) {
    die("Error: No database connection!");
}
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
} else {
    echo "Connected successfully<br>";
}
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Próbáljuk regisztrálni a felhasználót az adatbázisba a User osztály segítségével
    $registration = $user->register($username, $email, $password);

    if ($registration) {
        echo "Registration successful! <a href='login.php'>Bejelentkezés itt!</a>";
    } else {
        echo "This email address is already in use. Please try registering with a different email.";
    }
}
?>
<form method="POST">
    <label for="username">Felhasználónév:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Jelszó:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Regisztráció</button>
</form>






