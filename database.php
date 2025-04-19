<?php
function get_db_connection()
{
    static $dbh = null;
    if ($dbh === null) {
        try {
            // Lokális 
            //$dbh = new PDO(
            //  'mysql:host=localhost;dbname=menhely_db;charset=utf8mb4',
            // 'root',
            // '',
            // array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            //);

            // Tárhelyen
            $dbh = new PDO(
                'mysql:host=localhost;dbname=dbuser1web1gy',
                'dbuser1web1gy',
                'dbUser1@?',
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            die("Adatbázis kapcsolódási hiba. Kérjük, próbálja meg később.");
        }
    }
    return $dbh;
}

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
