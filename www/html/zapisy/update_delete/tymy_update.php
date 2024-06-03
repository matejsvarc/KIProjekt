<?php
ob_start(); // Začátek výstupního bufferu

// Připojení k databázi
$servername = "database";
$username = "admin";
$password = "heslo";
$database = "nba";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Nepodařilo se připojit k databázi: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM Tymy WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tym = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nazev = $_POST['nazev'];
    $datum_zalozeni = $_POST['datum_zalozeni'];
    $mesto = $_POST['mesto'];

    $sql = "UPDATE Tymy SET nazev = ?, datum_zalozeni = ?, mesto = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nazev, $datum_zalozeni, $mesto, $id);

    if ($stmt->execute()) {
        // Odeslání hlavičky pro přesměrování, ale až po tom, co skončí výstupní buffer
        header("Location: ../xmlUpravy.php");
        ob_end_flush(); // Konec a vyprázdnění výstupního bufferu
        exit();
    } else {
        echo "Chyba při aktualizaci týmu: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Úprava týmu</title>
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
        input[type="date"] {
            padding: 10px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            width: 97%;
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
        <h1>Úprava týmu</h1>
        <form action="tymy_update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $tym['ID']; ?>">
            <label for="nazev">Název:</label>
            <input type="text" name="nazev" value="<?php echo $tym['nazev']; ?>" required>
            <label for="datum_zalozeni">Datum založení:</label>
            <input type="date" name="datum_zalozeni" value="<?php echo $tym['datum_zalozeni']; ?>" required>
            <label for="mesto">Město:</label>
            <input type="text" name="mesto" value="<?php echo $tym['mesto']; ?>" required>
            <input type="submit" value="Upravit">
        </form>
        <a href="../xmlUpravy.php" class="back-link">Zpět</a>
    </div>
</body>

</html>