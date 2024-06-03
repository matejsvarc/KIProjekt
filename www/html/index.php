<?php
require '../prolog.php';
require INC . '/database.php';

// Funkce pro získání dat z databáze
function gethraciatymy($conn)
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
$hraciatymy = gethraciatymy($conn);

// Uzavření spojení s databází
$conn->close();

// Vytvoření XML dokumentu
$xml = new DOMDocument();
$xml->formatOutput = true;
$root = $xml->createElement('data');
$xml->appendChild($root);

foreach ($hraciatymy as $playerAndTeam) {
  $row = $xml->createElement('row');
  foreach ($playerAndTeam as $key => $value) {
    $child = $xml->createElement($key, htmlspecialchars($value));
    $row->appendChild($child);
  }
  $root->appendChild($row);
}

// Uložení XML do souboru
$xml->save('hraciatymy.xml');

// Provedení XSL transformace
$xsl = new DOMDocument();
$xsl->load('style.xsl');

$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl);
echo $proc->transformToXML($xml);
