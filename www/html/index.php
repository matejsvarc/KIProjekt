<?php // úvodní stránka:
require '../prolog.php';
require INC . '/database.php';

// Dotaz na získání dat z tabulky Hraci
$sql = "SELECT * FROM Hraci";
$result = $conn->query($sql);

// Zpracování výsledku dotazu
if ($result->num_rows > 0) {
  // Výpis výsledků
  while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row["ID"] . " - Jméno: " . $row["jmeno"] . " - Příjmení: " . $row["prijmeni"] . " - ID_tym: " . $row["ID_tym"] . " - ID_kontrakt: " . $row["ID_kontrakt"] . " - ID_statistiky: " . $row["ID_statistiky"] . "<br>";
  }
} else {
  echo "0 výsledků";
}

// Uzavření spojení s databází
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <a href="zapisy/xmlUpload.php">test</a>
</body>

</html>