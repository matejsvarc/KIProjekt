<?php
// Funkce pro zpracování chybového hlášení
function handleError($message)
{
    echo "<p style='color: red;'>Chyba: $message</p>";
    exit();
}

// Kontrola, zda byl odeslán formulář
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kontrola, zda byl nahrán XML soubor
    if (isset($_FILES["xml_file"])) {
        $xmlFile = $_FILES["xml_file"]["tmp_name"];

        // Validace XML pomocí XSD schématu
        $xsdSchema = "validace/tymy.xsd"; // Název XSD souboru
        $dom = new DOMDocument();
        $dom->load($xmlFile);
        if (!$dom->schemaValidate($xsdSchema)) {
            handleError("XML soubor není validní podle schématu.");
        }

        // Načtení dat z XML souboru
        $xmlData = simplexml_load_file($xmlFile);
        foreach ($xmlData->tym as $tym) {
            $nazev = $tym->nazev;
            $datum_zalozeni = $tym->datum_zalozeni;
            $mesto = $tym->mesto;

            // Připojení k databázi
            $servername = "database";
            $username = "admin";
            $password = "heslo";
            $database = "nba";
            $conn = new mysqli($servername, $username, $password, $database);
            if ($conn->connect_error) {
                handleError("Nepodařilo se připojit k databázi: " . $conn->connect_error);
            }

            // Příprava dotazu pro vložení týmu do databáze
            $stmt = $conn->prepare("INSERT INTO Tymy (nazev, datum_zalozeni, mesto) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nazev, $datum_zalozeni, $mesto);

            // Vložení dat do tabulky Tymy
            if (!$stmt->execute()) {
                handleError("Chyba při vkládání týmu do databáze: " . $stmt->error);
            }

            // Uzavření spojení s databází
            $stmt->close();
            $conn->close();
        }

        echo "<p>Týmy byly úspěšně zapsány do databáze.</p>";
    } else {
        handleError("Nahrání XML souboru se nepodařilo.");
    }
}
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zápis týmu</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Přidej vlastní styly zde */
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col justify-center items-center">
    <h1 class="text-3xl font-bold mb-8">Zápis týmu</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="xml_file">Vyberte XML soubor:</label>
            <input type="file" name="xml_file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="flex items-center justify-between">
            <input type="submit" value="Nahrát a zapsat týmy" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <a href="../index.php">Zpět</a>
            </button>
        </div>
    </form>
</body>

</html>