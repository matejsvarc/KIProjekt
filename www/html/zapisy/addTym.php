<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nazev = $_POST['nazev'];
    $datum_zalozeni = $_POST['datum_zalozeni'];
    $mesto = $_POST['mesto'];

    // Připojení k databázi
    $servername = "database";
    $username = "admin";
    $password = "heslo";
    $database = "nba";
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Nepodařilo se připojit k databázi: " . $conn->connect_error);
    }

    // Příprava dotazu pro vložení týmu do databáze
    $stmt = $conn->prepare("INSERT INTO Tymy (nazev, datum_zalozeni, mesto) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nazev, $datum_zalozeni, $mesto);

    // Vložení dat do tabulky Tymy
    if ($stmt->execute()) {
        echo "Tým byl úspěšně přidán.";
    } else {
        echo "Chyba při přidávání týmu: " . $conn->error;
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
    <title>Přidat tým</title>
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
        input[type="number"],
        input[type="date"] {
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

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #3b82f6;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Přidat tým</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nazev">Název:</label>
            <input type="text" name="nazev" required>
            <label for="datum_zalozeni">Datum založení:</label>
            <input type="date" name="datum_zalozeni" required>
            <label for="mesto">Město:</label>
            <input type="text" name="mesto" required>
            <input type="submit" value="Přidat tým">
            <a href="../index.php">Zpět</a>
        </form>
    </div>
</body>

</html>