<?php
// Připojení k databázi
$servername = "database";
$username = "admin";
$password = "heslo";
$database = "nba";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Nepodařilo se připojit k databázi: " . $conn->connect_error);
}

// Získání dat hráčů
$sql_hraci = "SELECT * FROM Hraci";
$result_hraci = $conn->query($sql_hraci);

// Získání dat týmů
$sql_tymy = "SELECT * FROM Tymy";
$result_tymy = $conn->query($sql_tymy);

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Úpravy dat</title>
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
            overflow-y: auto;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        h1 {
            color: #333333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }

        th {
            background-color: #f5f5f5;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }

        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Úpravy dat</h1>
        <div class="button-container">
            <a href="xmlUploadHraci.php" class="button">Zapis Hráči</a>
            <a href="xmlUploadTymy.php" class="button">Zapis Týmy</a>
            <a href="../index.php" class="button">Zpět</a>
        </div>
        <h2>Hráči</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Jméno</th>
                        <th>Příjmení</th>
                        <th>ID týmu</th>
                        <th>Body</th>
                        <th>Asistence</th>
                        <th>Doskoky</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_hraci->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['ID']; ?></td>
                            <td><?php echo $row['jmeno']; ?></td>
                            <td><?php echo $row['prijmeni']; ?></td>
                            <td><?php echo $row['ID_tym']; ?></td>
                            <td><?php echo $row['body']; ?></td>
                            <td><?php echo $row['asistence']; ?></td>
                            <td><?php echo $row['doskoky']; ?></td>
                            <td>
                                <a href="update_delete/hraci_update.php?id=<?php echo $row['ID']; ?>" class="button">Upravit</a>
                                <a href="update_delete/hraci_delete.php?id=<?php echo $row['ID']; ?>" class="button">Smazat</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <h2>Týmy</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Název</th>
                        <th>Datum založení</th>
                        <th>Město</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_tymy->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['ID']; ?></td>
                            <td><?php echo $row['nazev']; ?></td>
                            <td><?php echo $row['datum_zalozeni']; ?></td>
                            <td><?php echo $row['mesto']; ?></td>
                            <td>
                                <a href="update_delete/tymy_update.php?id=<?php echo $row['ID']; ?>" class="button">Upravit</a>
                                <a href="update_delete/tymy_delete.php?id=<?php echo $row['ID']; ?>" class="button">Smazat</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>