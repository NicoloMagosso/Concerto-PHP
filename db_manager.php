<?php

class DbManager
{
    private static $db;

    // Metodo per connettersi al database
    public static function connect()
    {
        if (self::$db === null) {
            // Leggi le credenziali dal file config.txt
            $config = parse_ini_file('config.txt');

            $dbHost = $config['db_host'];
            $dbName = $config['db_name'];
            $dbUser = $config['db_user'];
            $dbPass = $config['db_pass'];

            try {
                // Crea una nuova connessione PDO
                self::$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Errore di connessione al database: " . $e->getMessage());
            }
        }

        return self::$db;
    }
}
