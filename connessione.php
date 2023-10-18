<?php
class Database {

    private $host, $port, $username, $password;

    public function __construct($fileConfig)
    {
        $content = file_get_contents($fileConfig);
        $righe = explode("\n", $content);

        $this->host     = trim($righe[0]);
        $this->port    = trim($righe[1]);
        $this->username = trim($righe[2]);
        $this->password = trim($righe[3]);
    }

    public function connect($dbname) {
        
        $dsn = "mysql:dbname={$dbname};host={$this->host}";
               
        return new PDO($dsn, $this->username, $this->password);
        
    }
}

?>