<?php

// Includi la classe DbManager per la connessione al database
require_once('db_manager.php');

class Concerto
{
    // Attributi
    private $id;
    private $codice;
    private $titolo;
    private $descrizione;
    private $data_concerto;

    public function getId()
    {
        return $this->id;
    }

    public function getCodice()
    {
        return $this->codice;
    }

    public function setCodice($codice)
    {
        $this->codice = $codice;
    }

    public function getTitolo()
    {
        return $this->titolo;
    }

    public function setTitolo($titolo)
    {
        $this->titolo = $titolo;
    }

    public function getDescrizione()
    {
        return $this->descrizione;
    }

    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
    }

    public function getData()
    {
        return $this->data_concerto;
    }

    public function setData($data)
    {
        // Assicura che $data sia un oggetto DateTime
        if ($data instanceof DateTime) {
            $this->data_concerto = $data;
        } else {
            // Puoi implementare la conversione da stringa a DateTime se necessario
            $this->data_concerto = new DateTime($data);
        }
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

    // Metodo statico per creare un nuovo oggetto Concerto
    public static function Create($params)
    {
        try {
            $db = self::getDbConnection();

            // Prepara la query SQL per l'inserimento di un nuovo concerto
            $stmt = $db->prepare("INSERT INTO concerti (codice, titolo, descrizione, data_concerto) VALUES (:codice, :titolo, :descrizione, :data_concerto)");

            // Estrai i dati dall'array $params
            $codice = $params['codice'];
            $titolo = $params['titolo'];
            $descrizione = $params['descrizione'];
            $data = $params['data'];

            // Esegui la query con parametri preparati
            $stmt->bindParam(':codice', $codice);
            $stmt->bindParam(':titolo', $titolo);
            $stmt->bindParam(':descrizione', $descrizione);
            $stmt->bindParam(':data_concerto', $data);

            $stmt->execute();

            return Concerto::Find($db->lastInsertId());
        } catch (PDOException $e) {
            // Gestisco la caduta di connessione
            echo "Errore di connessione al database durante la creazione del concerto: " . $e->getMessage();
            return null;
        }
    }

    // Metodo statico per trovare un oggetto concerto per ID
    public static function Find($id)
    {
        $db = self::getDbConnection();

        // Prepara la query SQL per trovare un concerto per ID
        $stmt = $db->prepare("SELECT * FROM concerti WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchObject('Concerto');

        return $result;
    }

    // Metodo statico per trovare tutti gli oggetti concerto
    public static function FindAll()
    {
        $db = self::getDbConnection();

        // Esegui una query per trovare tutti i concerti
        $stmt = $db->query("SELECT * FROM concerti");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Concerto');

        return $result;
    }

    // Metodo per eliminare l'oggetto concerto
    public function Delete()
    {
        if ($this->id) {
            $db = self::getDbConnection();

            // Prepara la query SQL per eliminare un concerto per ID
            $stmt = $db->prepare("DELETE FROM concerti WHERE id = :id");
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
        }
    }

    // Metodo per aggiornare l'oggetto concerto
    public function Update($params)
    {
        // Aggiorna solo gli attributi specificati in $params
        $db = self::getDbConnection();
        $codice = $params['codice'];
        $titolo = $params['titolo'];
        $descrizione = $params['descrizione'];
        $data = $params['data'];

        // Prepara la query SQL per l'aggiornamento
        $stmt = $db->prepare("UPDATE concerti SET codice = :codice, titolo = :titolo, descrizione = :descrizione, data_concerto = :data_concerto WHERE id = :id");
        $stmt->bindParam(':codice', $codice);
        $stmt->bindParam(':titolo', $titolo);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':data_concerto', $data);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

    // Metodo per visualizzare l'oggetto Concerto
    public function Show()
    {
        echo "ID: {$this->getId()}\n";
        echo "Codice: {$this->getCodice()}\n";
        echo "Titolo: {$this->getTitolo()}\n";
        echo "Descrizione: {$this->getDescrizione()}\n";
        echo "Data: {$this->getData()}\n";
    }
}
