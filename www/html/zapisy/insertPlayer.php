<?php
require '../../prolog.php';
require INC . '../../include/database.php';

// Funkce pro zpracování chybového hlášení
function handleError($message)
{
    echo "<p style='color: red;'>Chyba: $message</p>";
    exit();
}

// Funkce pro získání sloupců tabulky
function getTableColumns($table, $conn)
{
    $result = $conn->query("DESCRIBE $table");
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    return $columns;
}

// Kontrola, zda byla odeslána data formuláře
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získání dat z formuláře
    $table = $_POST["table"];
    $columns = $_POST["columns"];
    $values = $_POST["values"];

    // Vytvoření SQL dotazu
    $columnsString = implode(", ", $columns);
    $placeholders = implode(", ", array_fill(0, count($values), "?"));

    $stmt = $conn->prepare("INSERT INTO $table ($columnsString) VALUES ($placeholders)");

    // Dynamicky vázání parametrů
    $types = str_repeat('s', count($values)); // Předpokládáme, že všechny vstupy jsou řetězce
    $stmt->bind_param($types, ...$values);

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
    <script>
        async function loadFormFields() {
            const table = document.getElementById('table').value;
            const formFields = document.getElementById('formFields');

            formFields.innerHTML = ''; // Vymazání stávajících polí

            try {
                const response = await fetch('get_columns.php?table=' + table);
                const columns = await response.json();

                columns.forEach(column => {
                    const div = document.createElement('div');
                    div.className = 'mb-4';
                    div.innerHTML = `
                        <label for="${column}" class="block text-gray-700 font-semibold mb-2">${column}:</label>
                        <input type="text" id="${column}" name="values[]" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-blue-500">
                        <input type="hidden" name="columns[]" value="${column}">
                    `;
                    formFields.appendChild(div);
                });
            } catch (error) {
                console.error('Error loading form fields:', error);
            }
        }
    </script>
</head>

<body class="bg-gray-100" onload="loadFormFields()">
    <div class="container mx-auto mt-8">
        <div class="max-w-md mx-auto bg-white shadow-md overflow-hidden rounded-md">
            <div class="bg-blue-500 text-white py-4 px-6">
                <h3 class="text-xl font-semibold text-center">Vkládání dat do databáze</h3>
            </div>
            <div class="p-6">
                <form action="process.php" method="post">
                    <div class="mb-4">
                        <label for="table" class="block text-gray-700 font-semibold mb-2">Vyberte tabulku:</label>
                        <select name="table" id="table" class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:border-blue-500" onchange="loadFormFields()">
                            <option value="Hraci">Hraci</option>
                            <option value="Kontrakty">Kontrakty</option>
                            <option value="statistiky">statistiky</option>
                            <option value="Tymy">Tymy</option>
                            <option value="Zapasy">Zapasy</option>
                        </select>
                    </div>
                    <div id="formFields"></div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Vložit do databáze</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>