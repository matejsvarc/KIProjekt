<?php
require '../../prolog.php';
require INC . '../../include/database.php';

// Funkce pro zpracování chybového hlášení
function handleError($message)
{
    echo "<p style='color: red;'>Chyba: $message</p>";
    exit();
}

// Kontrola, zda byla odeslána data formuláře
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Získání dat z formuláře
    $table = $_POST["table"];
    $data = $_POST["data"];

    // Vložení dat do databáze
    $stmt = $conn->prepare("INSERT INTO $table (nazev_sloupce) VALUES (?)"); // Nahraďte 'nazev_sloupce' skutečným názvem sloupce, do kterého chcete vkládat data
    $stmt->bind_param("s", $data);
    if (!$stmt->execute()) {
        handleError("Chyba při vkládání dat do databáze: " . $stmt->error);
    }

    // Uzavření spojení s databází
    $stmt->close();
    $conn->close();

    echo "<p>Data byla úspěšně vložena do tabulky $table.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vkládání dat do databáze</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <div class="max-w-md mx-auto bg-white shadow-md overflow-hidden rounded-md">
            <div class="bg-blue-500 text-white py-4 px-6">
                <h3 class="text-xl font-semibold text-center">Vkládání dat do databáze</h3>
            </div>
            <div class="p-6">
                <form action="process.php" method="post">
                    <div class="mb-4">
                        <label for="table">Vyberte tabulku:</label>
                        <select name="table" id="table" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-blue-500">
                            <option value="Tymy">Týmy</option>
                            <option value="Hraci">Hráči</option>
                            <option value="Kontrakty">Kontrakty</option>
                            <option value="Zapasy">Zápasy</option>
                            <option value="statistiky">Statistiky</option>
                        </select>
                    </div>
                    <!-- Pole pro vkládání dat -->
                    <div class="mb-4">
                        <label for="data">Data:</label>
                        <textarea id="data" name="data" rows="4" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-blue-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Vložit do databáze</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>