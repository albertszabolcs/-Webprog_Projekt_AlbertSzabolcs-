<?php

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register($username, $email, $password)
    {
        // Ellenőrizzük, hogy az email már létezik-e az adatbázisban
        if ($this->emailExists($email)) {
            return false;  // Ha létezik, nem engedjük a regisztrációt
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            // Ha a készítés nem sikerült, hibaüzenetet adunk
            die("Error preparing the query: " . $this->conn->error);
        }

        $stmt->bind_param("sss", $username, $email, $passwordHash);

        if (!$stmt->execute()) {
            // Ha a végrehajtás nem sikerült, hibaüzenetet adunk
            die("Error executing the registration query: " . $stmt->error);
        }

        $stmt->close();

        return true;  // Sikeres regisztráció
    }

    private function emailExists($email)
    {
        $sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing the query: " . $this->conn->error);

            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                die("Error executing the query:: " . $stmt->error);
            }

            $stmt->store_result();
            $exists = $stmt->num_rows > 0;
            $stmt->close();

            return $exists;
        }
    }
        public function login($email, $password)
    {
        /*Az email cim alapjan megkeresi a felhasznalot az adatbazisban*/
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Előkészítési hiba: " . $this->conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        /*Ellenorzi, hpgy a jelszo megegyik-e az adatbazisban tarolt
        titkositott jelszoval*/
        if ($user && password_verify($password, $user['password_hash'])) {
            /*Visszateriti a felhasznalo azonositojat*/
            return $user['id'];
        }
        /*Sikertelen bejelentkezesnel, pedig false-t*/
        return false;
    }
}

?>
