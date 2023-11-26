<?php
include 'concerto.php';

function MostraMenu()
{
    echo "=========================MENU'==========================\n";
    echo "| 1) Crea un record                                    |\n";
    echo "| 2) Mostra un record                                  |\n";
    echo "| 3) Modifica un record                                |\n";
    echo "| 4) Elimina un record                                 |\n";
    echo "| 5) Mostra tutti i records presenti nella tabella     |\n";
    echo "| 0) Termina il programma                              |\n";
    echo "========================================================\n";
}

function CreateRecord()
{
    // Creare un nuovo record
    $params = [
        'codice' => readline("Codice: "),
        'titolo' => readline("Titolo: "),
        'descrizione' => readline("Descrizione: "),
        'data' => readline("Data (utilizza il formato: YYYY-MM-DD): "),
        'sala' => readline("Sala: "),
    ];
    $concerto = Concerto::Create($params);
    echo "Record creato con successo.\n";
}

function MostraRecord()
{
    // Mostrare un record
    $id = readline("ID del record da visualizzare: ");
    $concerto = Concerto::Find($id);
    if ($concerto) {
        $concerto->Show();
    } else {
        echo "Record non trovato.\n";
    }
}

function ModificaRecord()
{
    // Modificare un record
    $id = readline("ID del record da modificare: ");
    $concerto = Concerto::Find($id);
    if ($concerto) {

        $codice = readline("Vecchio codice: " . $concerto->getCodice() . ". Nuovo codice (premere invio se si vuole mantenere il vecchio): ");
        $titolo = readline("Vecchio titolo: " . $concerto->getTitolo() . ". Nuovo titolo (premere invio se si vuole mantenere il vecchio): ");
        $descrizione = readline("Vecchia descrizione: " . $concerto->getDescrizione() . ". Nuova descrizione (premere invio se si vuole mantenere quella vecchia): ");
        $sala = readline("Vecchia sala: " . $concerto->getSala() . ". Nuova sala (premere invio se si vuole mantenere quella vecchia): ");
        $data = readline("Vecchia data: " . $concerto->getData() . ". Nuova data (utilizza il formato: YYYY-MM-DD) (premere invio se si vuole mantenere quella vecchia): ");
        echo "===================================\n";

        $params = [
            'codice' => (!empty($codice)) ? $codice : $concerto->getCodice(), // il punto di domanda è un operatore ternario, se il codice non è vuoto lo salva nella variabile $codice sennò mantiene quello che c'era prima
            'titolo' => (!empty($titolo)) ? $titolo : $concerto->getTitolo(),
            'descrizione' => (!empty($descrizione)) ? $descrizione : $concerto->getDescrizione(),
            'data' => (!empty($data)) ? $data : $concerto->getData(),
            'sala' => (!empty($sala)) ? $sala : $concerto->getSala(),
        ];

        $concerto->Update($params);
        echo "Record modificato con successo.\n";
    } else {
        echo "Record non trovato.\n";
    }
}

function DeleteRecord()
{
    // Eliminare un record
    $id = readline("ID del record da eliminare: ");
    $concerto = Concerto::Find($id);
    if ($concerto) {
        $concerto->Delete();
        echo "Record eliminato con successo.\n";
    } else {
        echo "Record non trovato.\n";
    }
}

function ShowAll()
{
    // Mostrare tutti i record
    $concerti = Concerto::FindAll();

    foreach ($concerti as $concerto) {
        $concerto->Show();
        echo "-------------------\n";
    }
}

while (true) {
    MostraMenu();
    $choice = readline("Scelta: ");
    switch ($choice) {
        case 0:
            echo "Programma terminato.";
            exit(0);
            break;
        case 1:
            try {
                CreateRecord();
            } catch (Exception $x) {
                echo "Si è verificato un errore: " . $x->getMessage() . "\n";
            };
            break;
        case 2:
            try {
                MostraRecord();
            } catch (Exception $x) {
                echo "Si è verificato un errore: " . $x->getMessage() . "\n";
            };
            break;
        case 3:
            ShowAll();
            try {
                ModificaRecord();
            } catch (Exception $x) {
                echo "Si è verificato un errore: " . $x->getMessage() . "\n";
            };
            break;
        case 4:
            ShowAll();
            try {
                DeleteRecord();
            } catch (Exception $x) {
                echo "Si è verificato un errore: " . $x->getMessage() . "\n";
            };
            break;
        case 5:
            ShowAll();
            break;
    }
    readline();
}
