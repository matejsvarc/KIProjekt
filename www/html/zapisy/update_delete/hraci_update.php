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
    $sql = "SELECT * FROM Hraci WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $hrac = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $ID_tym = $_POST['ID_tym'];
    $body = $_POST['body'];
    $asistence = $_POST['asistence'];
    $doskoky = $_POST['doskoky'];

    $sql = "UPDATE Hraci SET jmeno = ?, prijmeni = ?, ID_tym = ?, body = ?, asistence = ?, doskoky = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiiii", $jmeno, $prijmeni, $ID_tym, $body, $asistence, $doskoky, $id);

    if ($stmt->execute()) {
        echo "Hráč byl úspěšně aktualizován.";
    } else {
        echo "Chyba při aktualizaci hráče: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: ../xmlUpravy.php");
    ob_end_flush(); // Konec a vyprázdnění výstupního bufferu
    exit();
}
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Úprava hráče</title>
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
        <h1>Úprava hráče</h1>
        <form action="hraci_update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $hrac['ID']; ?>">
            <label for="jmeno">Jméno:</label>
            <input type="text" name="jmeno" value="<?php echo $hrac['jmeno']; ?>" required>
            <label for="prijmeni">Příjmení:</label>
            <input type="text" name="prijmeni" value="<?php echo $hrac['prijmeni']; ?>" required>
            <label for="ID_tym">ID týmu:</label>
            <input type="number" name="ID_tym" value="<?php echo $hrac['ID_tym']; ?>" required>
            <label for="body">Body:</label>
            <input type="number" name="body" value="<?php echo $hrac['body']; ?>" required>
            <label for="asistence">Asistence:</label>
            <input type="number" name="asistence" value="<?php echo $hrac['asistence']; ?>" required>
            <label for="doskoky">Doskoky:</label>
            <input type="number" name="doskoky" value="<?php echo $hrac['doskoky']; ?>" required>
            <input type="submit" value="Upravit">
        </form>
        <a href="../xmlUpravy.php" class="back-link">Zpět</a>
    </div>
</body>

</html>