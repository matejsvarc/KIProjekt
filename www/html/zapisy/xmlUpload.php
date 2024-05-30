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

        // Příprava dotazů pro vložení dat do databáze
        $stmtHraci = $conn->prepare("INSERT INTO Hraci (jmeno, prijmeni, ID_tym, ID_kontrakt, ID_statistiky) VALUES (?, ?, ?, ?, ?)");
        $stmtTymy = $conn->prepare("INSERT INTO Tymy (nazev, datum_zalozeni, mesto) VALUES (?, ?, ?)");

        // Načtení XML a vložení dat do databáze
        $xml = simplexml_load_string($xmlContent);

        // Vložení dat do tabulky Tymy
        $teams = [];
        foreach ($xml->Tymy->Tym as $tym) {
            $nazev = (string)$tym->nazev;
            $datum_zalozeni = (string)$tym->datum_zalozeni;
            $mesto = (string)$tym->mesto;

            $stmtTymy->bind_param("sss", $nazev, $datum_zalozeni, $mesto);
            if (!$stmtTymy->execute()) {
                handleError("Chyba při vkládání dat do tabulky Tymy: " . $stmtTymy->error);
            }

            // Získání ID vloženého týmu
            $teams[] = $conn->insert_id;
        }

        // Vložení dat do tabulky Hraci
        foreach ($xml->Hraci->Hrac as $hrac) {
            $jmeno = (string)$hrac->jmeno;
            $prijmeni = (string)$hrac->prijmeni;
            $index_tymu = ((int)$hrac->ID_tym) - 1; // Předpokládáme, že ID_tym je index týmu v poli $teams
            if (!isset($teams[$index_tymu])) {
                handleError("Neplatný ID_tym: " . $hrac->ID_tym);
            }
            $ID_tym = $teams[$index_tymu];
            $ID_kontrakt = (int)$hrac->ID_kontrakt;
            $ID_statistiky = (int)$hrac->ID_statistiky;

            $stmtHraci->bind_param("ssiii", $jmeno, $prijmeni, $ID_tym, $ID_kontrakt, $ID_statistiky);
            if (!$stmtHraci->execute()) {
                handleError("Chyba při vkládání dat do tabulky Hraci: " . $stmtHraci->error);
            }
        }

        // Uzavření spojení s databází
        $stmtHraci->close();
        $stmtTymy->close();
        $conn->close();

        echo "<p>Data byla úspěšně nahrána do databáze.</p>";
    } else {
        handleError("Nepodařilo se nahrát soubor.");
    }
}
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nahrát XML soubor</title>
</head>

<body>
    <h1>Nahrát XML soubor</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        Vyberte XML soubor k nahrání:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Nahrát soubor" name="submit">
    </form>
    <br>
    <a href="../index.php">Zpět na úvodní stránku</a>
</body>

</html>