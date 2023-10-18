<?php
include 'connessione.php';
class Concerto {
    private static $id=0;
    private $codice;
    private $titolo;
    private $descrizione;
    private $data_concerto;

    private function __construct($codice, $titolo, $descrizione, $data_concerto) {
        self::$id++;
        $this->codice = $codice;
        $this->titolo = $titolo;
        $this->descrizione = $descrizione;
        $this->data_concerto = $data_concerto;
    }

    public static function Create($codice, $titolo, $descrizione, $data_concerto) {
        $db= new Database("configurazione.txt");
        $concerto=new Concerto($codice, $titolo, $descrizione, $data_concerto);
        $pdo=$db->connect("OrganizzazioneConcerto");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $query = "INSERT INTO concerti (_codice, _titolo, _descrizione, _data) VALUES (:codice, :titolo, :descrizione, :data_concerto)";
        $stmt = $pdo->prepare($query);
    
        $stmt->bindParam(':codice', $codice, PDO::PARAM_STR);
        $stmt->bindParam(':titolo', $titolo, PDO::PARAM_STR);
        $stmt->bindParam(':descrizione', $descrizione, PDO::PARAM_STR);
        $stmt->bindParam(':data_concerto', $data_concerto, PDO::PARAM_STR);
    
        $stmt->execute();
        return $concerto;
    }

    public static function Delete($id)
    {
        $query = "DELETE FROM organizzazioneconcerto.concerti WHERE $id=:id";
    }

}

?>