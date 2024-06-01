<?php
require '../prolog.php';
require INC . '/database.php';

// Funkce pro výpis tabulky
function displayTable($conn, $tableName)
{
  $sql = "SELECT * FROM $tableName";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<h2>Tabulka: $tableName</h2>";
    echo "<table border='1'>";
    echo "<tr>";
    // Získání názvů sloupců
    while ($fieldInfo = $result->fetch_field()) {
      echo "<th>" . $fieldInfo->name . "</th>";
    }
    echo "</tr>";

    // Výpis dat
    $rowCount = 0;
    while ($row = $result->fetch_assoc()) {
      if ($rowCount % 5 === 0) {
        echo "<tbody>";
      }
      echo "<tr>";
      foreach ($row as $cell) {
        echo "<td>" . $cell . "</td>";
      }
      echo "</tr>";
      if ($rowCount % 5 === 4) {
        echo "</tbody>";
      }
      $rowCount++;
    }
    echo "</table>";
  } else {
    echo "<h2>Tabulka: $tableName</h2>";
    echo "0 výsledků";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Výpis dat z databáze</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f3f4f6;
      color: #333;
    }

    .container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th,
    td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f4f4f4;
    }

    .pagination {
      margin-top: 20px;
      display: flex;
      justify-content: center;
    }

    .pagination button {
      padding: 8px 12px;
      margin: 0 4px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .pagination button:hover {
      background-color: #0056b3;
    }

    .pagination button.current {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Výpis dat z databáze</h1>
    <a href="zapisy/xmlUpload.php" style="text-decoration: none; color: #007bff;">XML Upload</a>
    <a href="zapisy/insertPlayer.php" style="text-decoration: none; color: #007bff; position:relative; left:2rem">Insert Player</a>

    <?php
    // Výpis všech tabulek
    $tables = ['Hraci', 'Kontrakty', 'statistiky', 'Tymy', 'Zapasy'];
    foreach ($tables as $table) {
      displayTable($conn, $table);
    }

    // Uzavření spojení s databází
    $conn->close();
    ?>
  </div>

  <script>
    // Stránkování mezi tabulkami
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
      const rows = table.querySelectorAll('tbody tr');
      const pageSize = 5;
      const numPages = Math.ceil(rows.length / pageSize);

      let currentPage = 1;
      showPage(currentPage);

      const pagination = document.createElement('div');
      pagination.classList.add('pagination');

      for (let i = 1; i <= numPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.addEventListener('click', () => {
          currentPage = i;
          showPage(currentPage);
        });
        pagination.appendChild(button);
      }

      table.insertAdjacentElement('afterend', pagination);

      function showPage(page) {
        rows.forEach((row, index) => {
          const start = (page - 1) * pageSize;
          const end = start + pageSize;

          if (index >= start && index < end) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });

        const buttons = pagination.querySelectorAll('button');
        buttons.forEach(button => {
          if (parseInt(button.textContent) === page) {
            button.classList.add('current');
          } else {
            button.classList.remove('current');
          }
        });
      }
    });
  </script>
</body>

</html>