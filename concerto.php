<?php

// Includi la classe DbManager per la connessione al database
require_once('db_manager.php');

class Concerto
{
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
        // Assicurati che $data sia un oggetto DateTime
        if ($data instanceof DateTime) {
            $this->data_concerto = $data;
        } else {
            // Puoi implementare la conversione da stringa a DateTime se necessario
            $this->data_concerto = new DateTime($data);
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

        $db = DbManager::connect();
        
        $stmt = $db->prepare("SELECT COUNT(*) as exist FROM concerti WHERE codice=:c");  
        $stmt->bindParam(":c",$params['codice']);
        $stmt->execute();
        $var=$stmt->fetch(pdo::FETCH_ASSOC); 
        if($var['exist']>0)
        {
            throw new Exception("il codice è già presente\n");
        }
      
        // Crea un nuovo oggetto Concerto
        $concerto = new Concerto();
        $concerto->setCodice($codice);
        $concerto->setTitolo($titolo);
        $concerto->setDescrizione($descrizione);
        $concerto->setData($data);

        // Salva il nuovo oggetto nel database
        $db = DbManager::connect();
        
        $stmt = $db->prepare("INSERT INTO concerti (codice, titolo, descrizione, data_concerto) VALUES (?, ?, ?, ?)");

        $stmt->execute([$concerto->getCodice(), $concerto->getTitolo(), $concerto->getDescrizione(), $concerto->getData()->format('Y-m-d')]);

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
    public function Delete()
    {
        if ($this->id) {
            $db = DbManager::connect();
            $stmt = $db->prepare("DELETE FROM concerti WHERE id = ?");
            $stmt->execute([$this->id]);
        }
    }

    // Metodo per aggiornare l'oggetto Concerto
    public function Update($params)
    {
        // Aggiorna solo gli attributi specificati in $params
        if (isset($params['codice'])) {
            $this->setCodice($params['codice']);
        }
        $db = DbManager::connect();
        $stmt = $db->prepare("SELECT COUNT(*) as exist FROM concerti WHERE codice=:c");  
        $stmt->bindParam(":c",$params['codice']);
        $stmt->execute();
        $var=$stmt->fetch(pdo::FETCH_ASSOC); 
        if($var['exist']>0)
        {
            throw new Exception("il codice è già presente\n");
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

        $db = DbManager::connect();
        
        // Aggiorna il record nel database
        $stmt = $db->prepare("UPDATE concerti SET codice = ?, titolo = ?, descrizione = ?, data_concerto = ? WHERE id = ?");
        $stmt->execute([$this->getCodice(), $this->getTitolo(), $this->getDescrizione(), $this->getData()->format('Y-m-d'), $this->id]);
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
