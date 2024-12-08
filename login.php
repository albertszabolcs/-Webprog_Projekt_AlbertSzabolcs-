<?php
session_start();
include 'User.php';
include 'database_connect.php';

if (!isset($conn)) {
    die("Error: No database connection!");
}
if ($conn->connect_error) {
    die("Connection error " . $conn->connect_error);
} else {
    echo "Connected succesfully";
}
//User objektum létrehozása
$user = new User($conn);

//a login csak Post Metodust fogad
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Beolvasott adatok
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);  // Ellenőrizzük, hogy be van-e pipálva

    // Bejelentkezési kísérlet a User osztály login metódusával
    $userId = $user->login($email, $password);

    if ($userId) {
        // Sikeres bejelentkezés, elmentjük a user_id-t session-be
        $_SESSION['user_id'] = $userId;

        // Ha a "Remember me" be van pipálva, akkor cookie-t mentünk
        if ($rememberMe) {
            $cookieValue = base64_encode(json_encode([
                'email' => $email,
                'password' => $password
            ]));
            setcookie('remember_me', $cookieValue, time() + 3600, "/");  // 1 órás cookie
        } else {
            // Ha nincs bepipálva a "Remember me", töröljük a cookie-t (ha létezik)
            if (isset($_COOKIE['remember_me'])) {
                setcookie('remember_me', '', time() - 3600, "/");
            }
        }
        header("Location: ");
        exit;
    } else {
        echo "Hibás email vagy jelszó.";  // Hibaüzenet ha a bejelentkezés nem sikerült
    }
}

// Automatikus bejelentkezés, ha létezik a "remember_me" cookie
if (isset($_COOKIE['remember_me'])) {
    $cookieData = json_decode(base64_decode($_COOKIE['remember_me']), true);

    // Ellenőrizzük a cookie adatait
    if (!empty($cookieData['email']) && !empty($cookieData['password'])) {
        // Bejelentkezés az email és jelszó alapján
        $userId = $user->login($cookieData['email'], $cookieData['password']);

        if ($userId) {
            $_SESSION['user_id'] = $userId;  // Sikeres bejelentkezés esetén user_id mentése session-be


            header("Location: ");
            exit;
        }
    }
}
?>
<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <label for="remember_me">
        <input type="checkbox" id="remember_me" name="remember_me"> Remember me
    </label><br><br>

    <button type="submit">Bejelentkezés</button>
    <a href="register.php"><button type="button">Regisztráció</button></a>
</form>

