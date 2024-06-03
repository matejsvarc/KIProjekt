<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:template match="/">
    <html>
      <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Výpis hráčů a týmů</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
          }

          .container {
            max-width: 800px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
          }

          th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
          }

          th {
            background-color: #f5f5f5;
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
            background-color:#3b82f6;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px; 
          }
        </style>
      </head>
      <body>
        <div class="container">
          <h1>Výpis hráčů a týmů</h1>
          <div class="button-container">
            <a href="zapisy/xmlUploadHraci.php" class="button">Zapis Hráči</a>
            <a href="zapisy/xmlUploadTymy.php" class="button">Zapis Týmy</a>
            <a href="zapisy/xmlUpravy.php" class="button"> Upravy dat </a>
          </div>
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
              <xsl:for-each select="data/row">
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
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
