<?php
// Funkce pro zpracování chybového hlášení
function handleError($message)
{
    echo "<p style='color: red;'>Chyba: $message</p>";
    exit();
}

// Kontrola, zda byl odeslán formulář
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kontrola, zda byl nahrán soubor
    if ($_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK && is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
        // Cesta k XML souboru
        $xmlFile = $_FILES["fileToUpload"]["tmp_name"];

        // Cesta k XSD souboru pro validaci
        $xsdFile = "validace/hraci.xsd"; // Změňte podle skutečné cesty k XSD souboru

        // Validace XML souboru proti XSD schématu
        $doc = new DOMDocument();
        $doc->load($xmlFile);
        if (!$doc->schemaValidate($xsdFile)) {
            handleError("XML soubor není validní podle XSD schématu.");
        }

        // Načtení XML souboru
        $xmlContent = file_get_contents($xmlFile);

        // Připojení k databázi
        $servername = "database";
        $username = "admin";
        $password = "heslo";
        $database = "nba";
        $conn = new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            handleError("Nepodařilo se připojit k databázi: " . $conn->connect_error);
        }

        // Načtení XML dat
        $xml = simplexml_load_string($xmlContent);

        // Vložení týmů
        $stmtTeam = $conn->prepare("INSERT INTO Tymy (nazev, datum_zalozeni, mesto) VALUES (?, ?, ?)");
        foreach ($xml->Tymy->Tym as $tym) {
            $stmtTeam->bind_param("sss", $tym->nazev, $tym->datum_zalozeni, $tym->mesto);
            if (!$stmtTeam->execute()) {
                handleError("Chyba při vkládání týmu do databáze: " . $stmtTeam->error);
            }
        }
        $stmtTeam->close();

        // Vložení kontraktů
        $stmtContract = $conn->prepare("INSERT INTO Kontrakty (Castka, zacatek, konec) VALUES (?, ?, ?)");
        foreach ($xml->Kontrakty->Kontrakt as $kontrakt) {
            $stmtContract->bind_param("iss", $kontrakt->Castka, $kontrakt->zacatek, $kontrakt->konec);
            if (!$stmtContract->execute()) {
                handleError("Chyba při vkládání kontraktu do databáze: " . $stmtContract->error);
            }
        }
        $stmtContract->close();

        $stmtStats = $conn->prepare("INSERT INTO statistiky (ID_zapasu, body, asistence, doskoky) VALUES (?, ?, ?, ?)");
        foreach ($xml->Statistiky->Statistika as $stat) {
            $stmtStats->bind_param("iiii", $stat->ID_zapasu, $stat->body, $stat->asistence, $stat->doskoky);
            if (!$stmtStats->execute()) {
                handleError("Chyba při vkládání statistik do databáze: " . $stmtStats->error);
            }
        }
        $stmtStats->close();
        // Vložení hráčů
        $stmtPlayer = $conn->prepare("INSERT INTO Hraci (jmeno, prijmeni, ID_tym, ID_kontrakt, ID_statistiky) VALUES (?, ?, ?, ?, ?)");
        foreach ($xml->Hraci->Hrac as $hrac) {
            $stmtPlayer->bind_param("ssiii", $hrac->jmeno, $hrac->prijmeni, $hrac->ID_tym, $hrac->ID_kontrakt, $hrac->ID_statistiky);
            if (!$stmtPlayer->execute()) {
                handleError("Chyba při vkládání hráče do databáze: " . $stmtPlayer->error);
            }
        }
        $stmtPlayer->close();

        // Vložení zápasů
        $stmtMatch = $conn->prepare("INSERT INTO Zapasy (ID_hrace, ID_statistiky, datum) VALUES (?, ?, ?)");
        foreach ($xml->Zapasy->Zapas as $zapas) {
            $stmtMatch->bind_param("iis", $zapas->ID_hrace, $zapas->ID_statistiky, $zapas->datum);
            if (!$stmtMatch->execute()) {
                handleError("Chyba při vkládání zápasu do databáze: " . $stmtMatch->error);
            }
        }
        $stmtMatch->close();

        // Uzavření spojení s databází
        $conn->close();

        echo "<p>Data byla úspěšně nahrána do databáze.</p>";
    } else {
        handleError("Nepodařilo se nahrát soubor.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nahrávání XML souborů</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <div class="max-w-md mx-auto bg-white shadow-md overflow-hidden rounded-md">
            <div class="bg-blue-500 text-white py-4 px-6">
                <h3 class="text-xl font-semibold text-center">Nahrávání XML souborů</h3>
            </div>
            <div class="p-6">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="fileToUpload" class="block text-gray-700 font-semibold mb-2">Vyberte XML soubor:</label>
                        <input type="file" id="fileToUpload" name="fileToUpload" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Nahrát soubor</button>
                </form>
                <div class="mt-4">
                    <a href="../index.php" class="text-blue-500 hover:underline">Zpět</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>