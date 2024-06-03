<?php
require '../prolog.php';
require INC . '/database.php';

// Funkce pro generování XML
function generateXML($conn, $tableName)
{
  $xml = new DOMDocument('1.0', 'UTF-8');
  $xml->formatOutput = true;

  $root = $xml->createElement($tableName);
  $xml->appendChild($root);

  $sql = "SELECT * FROM $tableName";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $entry = $xml->createElement("entry");
      foreach ($row as $column => $value) {
        $node = $xml->createElement($column, htmlspecialchars($value));
        $entry->appendChild($node);
      }
      $root->appendChild($entry);
    }
  }

  return $xml->saveXML();
}

// Generování XML pro každou tabulku
$tables = ['Hraci', 'Kontrakty', 'statistiky', 'Tymy', 'Zapasy'];
$xmlOutput = new DOMDocument('1.0', 'UTF-8');
$root = $xmlOutput->createElement('database');
$xmlOutput->appendChild($root);

foreach ($tables as $table) {
  $tableXML = new DOMDocument();
  $tableXML->loadXML(generateXML($conn, $table));
  $root->appendChild($xmlOutput->importNode($tableXML->documentElement, true));
}

// Uložení XML do souboru
$xmlOutput->save('database.xml');

// Uzavření spojení s databází
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Výpis dat z databáze</title>
</head>

<body>
  <div class="container">
    <h1>Výpis dat z databáze</h1>
    <a href="zapisy/xmlUpload.php" style="text-decoration: none; color: #007bff;">XML Upload</a>
    <a href="zapisy/insertPlayer.php" style="text-decoration: none; color: #007bff; position:relative; left:2rem">Insert Player</a>

    <!-- Zde se načte a zobrazí XML pomocí XSL -->
    <div id="data-output"></div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const xhr = new XMLHttpRequest();
      xhr.open('GET', 'database.xml', true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const xsltProcessor = new XSLTProcessor();
          const xsl = new XMLHttpRequest();
          xsl.open('GET', 'vypis.xsl', true);
          xsl.onreadystatechange = function() {
            if (xsl.readyState === 4 && xsl.status === 200) {
              const xslDocument = xsl.responseXML;
              xsltProcessor.importStylesheet(xslDocument);

              const xmlDocument = xhr.responseXML;
              const resultDocument = xsltProcessor.transformToFragment(xmlDocument, document);
              document.getElementById('data-output').appendChild(resultDocument);
            }
          };
          xsl.send(null);
        }
      };
      xhr.send(null);
    });
  </script>
</body>

</html>