<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $ID_tym = $_POST['ID_tym'];
    $body = $_POST['body'];
    $asistence = $_POST['asistence'];
    $doskoky = $_POST['doskoky'];

    // Připojení k databázi
    $servername = "database";
    $username = "admin";
    $password = "heslo";
    $database = "nba";
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Nepodařilo se připojit k databázi: " . $conn->connect_error);
    }

    // Příprava dotazu pro vložení hráče do databáze
    $stmt = $conn->prepare("INSERT INTO Hraci (jmeno, prijmeni, ID_tym, body, asistence, doskoky) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiii", $jmeno, $prijmeni, $ID_tym, $body, $asistence, $doskoky);

    // Vložení dat do tabulky Hraci
    if ($stmt->execute()) {
        echo "Hráč byl úspěšně přidán.";
    } else {
        echo "Chyba při přidávání hráče: " . $conn->error;
    }

    // Uzavření spojení s databází
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přidat hráče</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 600px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            margin-bottom: 5px;
            color: #333333;
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 10px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        input[type="submit"]:hover {
            background-color: #2563eb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Přidat hráče</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="jmeno">Jméno:</label>
            <input type="text" name="jmeno" required>
            <label for="prijmeni">Příjmení:</label>
            <input type="text" name="prijmeni" required>
            <label for="ID_tym">ID týmu:</label>
            <input type="number" name="ID_tym" required>
            <label for="body">Body:</label>
            <input type="number" name="body" required>
            <label for="asistence">Asistence:</label>
            <input type="number" name="asistence" required>
            <label for="doskoky">Doskoky:</label>
            <input type="number" name="doskoky" required>
            <input type="submit" value="Přidat hráče">
            <a href="../index.php">Zpět</a>
        </form>
    </div>
</body>

</html>