<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" encoding="UTF-8" indent="yes"/>
  <xsl:template match="/">
    <html>
    <head>
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
      <xsl:for-each select="database/*">
        <h2>Tabulka: <xsl:value-of select="name()"/></h2>
        <table border="1">
          <tr>
            <xsl:for-each select="entry[1]/*">
              <th><xsl:value-of select="name()"/></th>
            </xsl:for-each>
          </tr>
          <xsl:for-each select="entry">
            <tr>
              <xsl:for-each select="*">
                <td><xsl:value-of select="."/></td>
              </xsl:for-each>
            </tr>
          </xsl:for-each>
        </table>
      </xsl:for-each>
    </div>
    </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
