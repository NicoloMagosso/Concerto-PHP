<?php

// Includi la classe DbManager per la connessione al database
require_once('db_manager.php');

class Concerto
{
    private $id;
    private $codice;
    private $titolo;
    private $descrizione;
    private $data;

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
        return $this->data;
    }

    public function setData($data)
    {
        // Assicurati che $data sia un oggetto DateTime
        if ($data instanceof DateTime) {
            $this->data = $data;
        } else {
            // Puoi implementare la conversione da stringa a DateTime se necessario
            $this->data = new DateTime($data);
        }
    }

    // Metodo statico per creare un nuovo oggetto Concerto
    public static function Create($params)
    {
        // Estrai i dati dall'array $params
        $codice = $params['codice'];
        $titolo = $params['titolo'];
        $descrizione = $params['descrizione'];
        $data = $params['data'];

        // Crea un nuovo oggetto Concerto
        $concerto = new Concerto();
        $concerto->setCodice($codice);
        $concerto->setTitolo($titolo);
        $concerto->setDescrizione($descrizione);
        $concerto->setData($data);

        // Salva il nuovo oggetto nel database
        $db = DbManager::connect();
        $stmt = $db->prepare("INSERT INTO concerti (codice, titolo, descrizione, data) VALUES (?, ?, ?, ?)");
        $stmt->execute([$concerto->getCodice(), $concerto->getTitolo(), $concerto->getDescrizione(), $concerto->getData()->format('Y-m-d H:i:s')]);

        // Ottieni l'ID generato per il nuovo record
        $concerto->id = $db->lastInsertId();

        return $concerto;
    }

    // Metodo statico per trovare un oggetto Concerto per ID
    public static function Find($id)
    {
        $db = DbManager::connect();
        $stmt = $db->prepare("SELECT * FROM concerti WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetchObject('Concerto');

        return $result;
    }

    // Metodo statico per trovare tutti gli oggetti Concerto
    public static function FindAll()
    {
        $db = DbManager::connect();
        $stmt = $db->query("SELECT * FROM concerti");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Concerto');

        return $result;
    }

    // Metodo per eliminare l'oggetto Concerto
    public function delete()
    {
        if ($this->id) {
            $db = DbManager::connect();
            $stmt = $db->prepare("DELETE FROM concerti WHERE id = ?");
            $stmt->execute([$this->id]);
        }
    }

    // Metodo per aggiornare l'oggetto Concerto
    public function update($params)
    {
        // Aggiorna solo gli attributi specificati in $params
        if (isset($params['codice'])) {
            $this->setCodice($params['codice']);
        }
        if (isset($params['titolo'])) {
            $this->setTitolo($params['titolo']);
        }
        if (isset($params['descrizione'])) {
            $this->setDescrizione($params['descrizione']);
        }
        if (isset($params['data'])) {
            $this->setData($params['data']);
        }

        // Aggiorna il record nel database
        $db = DbManager::connect();
        $stmt = $db->prepare("UPDATE concerti SET codice = ?, titolo = ?, descrizione = ?, data = ? WHERE id = ?");
        $stmt->execute([$this->getCodice(), $this->getTitolo(), $this->getDescrizione(), $this->getData(), $this->id]);
    }

    // Metodo per visualizzare l'oggetto Concerto
    public function show()
    {
        echo "ID: {$this->id}\n";
        echo "Codice: {$this->codice}\n";
        echo "Titolo: {$this->titolo}\n";
        echo "Descrizione: {$this->descrizione}\n";
        echo "Data: {$this->data}\n";
    }
}
?>