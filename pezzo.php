<?php

// Includi la classe DbManager per la connessione al database
require_once('db_manager.php');

class Pezzo
{
    private $id;
    private $codice;
    private $titolo;


    public function getId()
    {
        return $this->id;
    }

    public function getCodice()
    {
        return $this->codice;
    }

    public function getTitolo()
    {
        return $this->titolo;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCodice($codice)
    {
        $this->codice = $codice;
    }

    public function setTitolo($titolo)
    {
        $this->titolo = $titolo;
    }

    // Metodo statico per ottenere una connessione al database
    private static function getDbConnection()
    {
        static $db = null;
        if ($db === null) {
            $db = DbManager::connect();
        }
        return $db;
    }

    // Metodo statico per trovare un oggetto pezzo per ID
    public static function Find($id)
    {
        $db = self::getDbConnection();

        // Prepara la query SQL per trovare un pezzo per ID
        $stmt = $db->prepare("SELECT * FROM pezzi WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchObject('Pezzo');

        return $result;
    }

    // Metodo statico per trovare tutti gli oggetti pezzo
    public static function FindAll()
    {
        $db = self::getDbConnection();

        // Esegui una query per trovare tutti i pezzi
        $stmt = $db->query("SELECT * FROM pezzi");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Pezzo');

        return $result;
    }

    // Metodo per visualizzare l'oggetto pezzo
    public function Show()
    {
        echo "ID: {$this->getId()}\n";
        echo "Codice: {$this->getCodice()}\n";
    }
}
