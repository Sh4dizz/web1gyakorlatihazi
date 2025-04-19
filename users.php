<?php
require_once 'database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['page']) && $_GET['page'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}


$error = null;
$success = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($action === 'login') {
        try {
            $dbh = get_db_connection();
            $stmt = $dbh->prepare("SELECT id, username, firstname, lastname, password FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $password == $user['password']) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname']
                ];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Hibás felhasználónév vagy jelszó.';
            }
        } catch (PDOException $e) {
            $error = 'Adatbázis hiba történt a belépés során.';
        }
    } elseif ($action === 'register') {
        $firstname = sanitize_input($_POST['firstname'] ?? '');
        $lastname = sanitize_input($_POST['lastname'] ?? '');


        if (!$username || !$password || !$firstname || !$lastname) {
            $error = 'Minden mező kitöltése kötelező!';
        } else {
            try {
                $dbh = get_db_connection();

                $stmt = $dbh->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
                $stmt->execute(['username' => $username]);

                if ($stmt->fetchColumn() > 0) {
                    $error = 'Ez a felhasználónév már foglalt!';
                } else {

                    $stmt = $dbh->prepare("INSERT INTO users (username, password, firstname, lastname) 
                                         VALUES (:username, :password, :firstname, :lastname)");
                    $stmt->execute([
                        'username' => $username,
                        'password' => $password, // Nincs hashelés egyelőre,todo
                        'firstname' => $firstname,
                        'lastname' => $lastname
                    ]);

                    $success = 'Sikeres regisztráció! Most már beléphetsz.';
                }
            } catch (PDOException $e) {
                $error = 'Adatbázis hiba történt a regisztráció során.';
            }
        }
    }
}
