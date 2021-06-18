<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="- //W3C//DTD XHTML 1.0 Strict//EN" />
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>XSL podaci</title>
		<link rel="stylesheet" type="text/css" href="dizajn.css"/>
	</head>	
	
	<body>
		<div class="index-header">
        <!-- Can use html element header but layout is made using only div elements -->
        <div class="index-title">
            <a href="index.html"><img class="oscar-head-logo" src="assets/oscar_head_logo.png" alt="oscar-logo"/>
               <h2>OSCARI ZA NAJBOLJI STRANI FILM</h2>
            </a>
        </div>
    </div>		
			
	 <div class="index-navbar">
        <a class="link" href="index.html">Početna</a>
        <a class="link" href="obrazac.html">Pretraži oskarovce</a>
        <a class="link" href="podaci.xml">Katalog filmova</a>
        <a class="link" href="http://www.fer.unizg.hr/predmet/or">Otvoreno računarstvo</a>
        <a class="link" href="http://www.fer.unizg.hr" target="_blank">FER</a>
        <a class="link" href="mailto:leon.kranjcevic@fer.hr">E-mail</a>
    </div>
		<div class="responsive-table" > 
		<table>
		<tr>
                            <th>Naziv:</th>
                            <th>Redatelj:</th>
                            <th>Godina:</th>
                            <th>Originalni naziv:</th>
                            <th>Sinopsis:</th>
                            <th>Trajanje:</th>
                            <th>Jezik:</th>		
                            <th>Balkanski film:</th>
                            <th>Desetljeće:</th>
		</tr>
		
		<xsl:for-each select="/filmovi/film">
		<tr>
							<td>
              <xsl:value-of select="naziv" /></td>
<td>
								<xsl:value-of select="redatelj/ime_redatelj" />
								<br/>
								<xsl:value-of select="redatelj/broj_oscara" />
							</td>

                            <td><xsl:value-of select="godina" /></td>

                            <td><xsl:value-of select="originalni_naziv" /></td>

                            <td><xsl:value-of select="sinopsis" /></td>

                            <td><xsl:value-of select="trajanje" /></td>

                            <td>
								<xsl:for-each select="jezik/ime_jezika">
								<xsl:value-of select="text()"/>
								<br/>
								</xsl:for-each> 
							</td>	
									
							<td><xsl:value-of select="@balkanski_film"/></td>

                            <td><xsl:value-of select="@desetljece"/></td>
								
		</tr>
				</xsl:for-each>
				</table>
		</div>

		<div class="footer">
        <h4>Made by Leon Kranjčević</h4>
    </div>
      </body>
	  
    </html>
  </xsl:template>
</xsl:stylesheet> 