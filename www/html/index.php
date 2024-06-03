<?php
require '../prolog.php';
require INC . '/database.php';

// Funkce pro získání dat z databáze
function getPlayersAndTeams($conn)
{
  $sql = "SELECT Hraci.jmeno AS jmeno_hrace, Hraci.prijmeni AS prijmeni_hrace, Tymy.nazev AS nazev_tymu,
            Hraci.body, Hraci.asistence, Hraci.doskoky
            FROM Hraci
            INNER JOIN Tymy ON Hraci.ID_tym = Tymy.ID";
  $result = $conn->query($sql);

  $data = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }

  return $data;
}

// Získání dat
$playersAndTeams = getPlayersAndTeams($conn);

// Uzavření spojení s databází
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Výpis hráčů a týmů</title>
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }

    th,
    td {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>

  <div class="container">
    <h1>Výpis hráčů a týmů</h1>
    <button><a href="zapisy/xmlUploadHraci.php">Zapis Hráči</a></button>
    <button><a href="zapisy/xmlUploadTymy.php">Zapis Týmy</a></button>
    <table>
      <thead>
        <tr>
          <th>Jméno hráče</th>
          <th>Příjmení hráče</th>
          <th>Název týmu</th>
          <th>Body</th>
          <th>Asistence</th>
          <th>Doskoky</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($playersAndTeams as $playerAndTeam) : ?>
          <tr>
            <td><?php echo htmlspecialchars($playerAndTeam['jmeno_hrace']); ?></td>
            <td><?php echo htmlspecialchars($playerAndTeam['prijmeni_hrace']); ?></td>
            <td><?php echo htmlspecialchars($playerAndTeam['nazev_tymu']); ?></td>
            <td><?php echo htmlspecialchars($playerAndTeam['body']); ?></td>
            <td><?php echo htmlspecialchars($playerAndTeam['asistence']); ?></td>
            <td><?php echo htmlspecialchars($playerAndTeam['doskoky']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>

</html>