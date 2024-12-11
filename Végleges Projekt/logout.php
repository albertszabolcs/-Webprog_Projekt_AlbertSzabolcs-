<?php


session_start();

// Felhasználói munkamenet törlése
if (!empty($_SESSION)) {
    $_SESSION = []; // Munkamenet adatok kiürítése
    session_unset(); // Session változók törlése
}

// 'Remember me' cookie törlése
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/', '', false, true); // Cookie törlése
}
session_destroy(); // A munkamenet lezárása

// Átirányítás a bejelentkezési oldalra
header("Location: login.php");
exit;


