<!-- styling.xsl -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template match="/">
    <html>
      <head>
        <title>Výpis hráčů a týmů</title>
        <style>
          table {
            border-collapse: collapse;
            width: 100%;
          }
          th, td {
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
            <xsl:for-each select="data/item">
              <tr>
                <td><xsl:value-of select="jmeno_hrace"/></td>
                <td><xsl:value-of select="prijmeni_hrace"/></td>
                <td><xsl:value-of select="nazev_tymu"/></td>
                <td><xsl:value-of select="body"/></td>
                <td><xsl:value-of select="asistence"/></td>
                <td><xsl:value-of select="doskoky"/></td>
              </tr>
            </xsl:for-each>
          </tbody>
        </table>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
