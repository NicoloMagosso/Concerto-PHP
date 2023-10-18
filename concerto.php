<?php
include 'connessione.php';
class Concerto {
    private static $id=0;
    private $codice;
    private $titolo;
    private $descrizione;
    private $data;

    private function __construct($codice, $titolo, $descrizione, $data) {
        self::$id++;
        $this->codice = $codice;
        $this->titolo = $titolo;
        $this->descrizione = $descrizione;
        $this->data = $data;
    }

    public static function Create($codice, $titolo, $descrizione, $data) {
        $db= new Database("configurazione.txt");
        $concerto=new Concerto($codice, $titolo, $descrizione, $data);
        $pdo=$db->connect("OrganizzazioneConcerto");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $query = "INSERT INTO concerti (_codice, _titolo, _descrizione, _data) VALUES (:codice, :titolo, :descrizione, :data)";
        $stmt = $pdo->prepare($query);
    
        $stmt->bindParam(':codice', $codice, PDO::PARAM_STR);
        $stmt->bindParam(':titolo', $titolo, PDO::PARAM_STR);
        $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
        $stmt->bindParam(':data', $data, PDO::PARAM_STR);
    
        $stmt->execute();
        return $concerto;
    }
}

?>