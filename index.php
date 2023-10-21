<?php
include 'concerto.php';

echo "Premi 1 per creare un record\n";
echo "Premi 2 per mostrare un record\n";
echo "Premi 3 per modificare un record\n";
echo "Premi 4 per eliminare un record\n";
echo "Premi 5 per mostrare tutti i records presenti nella tabella\n";
echo "Premi 0 per terminare il programma\n";

$choice = readline("Scelta: ");
if ($choice == 1) {
    // Creare un nuovo record
    $params = [
        'codice' => readline("Codice: "),
        'titolo' => readline("Titolo: "),
        'descrizione' => readline("Descrizione: "),
        'data' => readline("Data (utilizza il formato: YYYY-MM-DD HH:MM:SS): "),
    ];

    $concerto = Concerto::Create($params);
    echo "Record creato con successo.\n";
} elseif ($choice == 2) {
    // Mostrare un record
    $id = readline("ID del record da visualizzare: ");
    $concerto = Concerto::Find($id);
    if ($concerto) {
        $concerto->show();
    } else {
        echo "Record non trovato.\n";
    }
} elseif ($choice == 3) {
    // Modificare un record
    $id = readline("ID del record da modificare: ");
    $concerto = Concerto::Find($id);
    if ($concerto) {

        $codice = readline("Nuovo codice: ");
        $titolo = readline("Nuovo titolo: ");
        $descrizione = readline("Nuova descrizione: ");
        $data = readline("Nuova data (utilizza il formato: YYYY-MM-DD HH:MM:SS): ");
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $input);

        if ($date === false) {

            echo "La data inserita non è nel formato corretto (YYYY-MM-DD HH:MM:SS)\n";
        } else {

            $params = [
                $codice,
                $titolo,
                $descrizione,
                $data,
            ];
            $concerto->update($params);
            echo "Record modificato con successo.\n";

        }
    } else {
        echo "Record non trovato.\n";
    }
} elseif ($choice == 4) {
    // Eliminare un record
    $id = readline("ID del record da eliminare: ");
    $concerto = Concerto::Find($id);
    if ($concerto) {
        $concerto->delete();
        echo "Record eliminato con successo.\n";
    } else {
        echo "Record non trovato.\n";
    }
} elseif ($choice == 5) {
    // Mostrare tutti i record
    $concerti = Concerto::FindAll();
    foreach ($concerti as $concerto) {
        $concerto->show();
        echo "-------------------\n";
    }
} else {
    echo "Programma terminato.\n";
}
?>
