<?php

// Includi la classe DbManager per la connessione al database
require_once('db_manager.php');

class Sala
{
    private $id;
    private $nome;
    private $codice;
    private $capienza;


    public function setID($id)
    {
        $this->id = $id;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }
    public function setCodice($codice)
    {
        $this->codice = $codice;
    }
    public function setCapienza($capienza)
    {
        $this->capienza = $capienza;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function getCodice()
    {
        return $this->codice;
    }
    public function getCapienza()
    {
        return $this->capienza;
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

    // Metodo statico per trovare un oggetto sala per ID
    public static function Find($id)
    {
        $db = self::getDbConnection();

        // Prepara la query SQL per trovare una sala per ID
        $stmt = $db->prepare("SELECT * FROM sale WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchObject('Sala');

        return $result;
    }

    // Metodo per eliminare l'oggetto sala
    public function Delete()
    {
        if ($this->id) {
            $db = self::getDbConnection();

            // Prepara la query SQL per eliminare una sala per ID
            $stmt = $db->prepare("DELETE FROM sale WHERE id = :id");
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
        }
    }

    // Metodo per visualizzare l'oggetto sala
    public function Show()
    {
        echo "ID: {$this->getId()}\n";
        echo "Codice: {$this->getCodice()}\n";
    }
}
