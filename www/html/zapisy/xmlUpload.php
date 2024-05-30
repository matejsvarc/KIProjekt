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

        // Příprava dotazu pro vložení dat do databáze
        $stmt = $conn->prepare("INSERT INTO Hraci (jmeno, prijmeni, ID_tym, ID_kontrakt, ID_statistiky) VALUES (?, ?, ?, ?, ?)");

        // Vložení dat z XML do databáze
        // (Předpokládáme strukturu XML souboru podobnou databázi)
        $xml = simplexml_load_string($xmlContent);
        foreach ($xml->children() as $hrac) {
            $jmeno = $hrac->jmeno;
            $prijmeni = $hrac->prijmeni;
            $ID_tym = $hrac->ID_tym;
            $ID_kontrakt = $hrac->ID_kontrakt;
            $ID_statistiky = $hrac->ID_statistiky;

            $stmt->bind_param("ssiii", $jmeno, $prijmeni, $ID_tym, $ID_kontrakt, $ID_statistiky);
            if (!$stmt->execute()) {
                handleError("Chyba při vkládání dat do databáze: " . $stmt->error);
            }
        }

        // Uzavření spojení s databází
        $stmt->close();
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
    <title>Nahrát XML soubor</title>
</head>

<body>
    <h1>Nahrát XML soubor</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        Vyberte XML soubor k nahrání:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Nahrát soubor" name="submit">
    </form>
</body>

</html>