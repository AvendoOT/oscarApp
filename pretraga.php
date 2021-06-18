<?php
	error_reporting (E_ALL);
	include_once ('funkcije.php');
    ini_set('user_agent', 'MyBrowser v42.0.4711');
	
	$domDocument = new DOMDocument();
  	$domDocument->load('podaci.xml');

  	$xPath = new DOMXPath($domDocument);

  	$query = queryGenerator();
  	$result = $xPath->query($query);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta charset="utf-8">
    <!-- HTML5 saved UTF-8 with BOM-->
    <link rel="stylesheet" type="text/css" href="dizajn.css">
    <title>OSCAR search</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

	<body>
    <script src="detalji.js"></script>
    <div class="index-header">
        <!-- Can use html element header but layout is made using only div elements -->
        <div class="index-title">
            <a href="index.html"><img class="oscar-head-logo" src="assets/oscar_head_logo.png" alt="oscar-logo">
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
    <h2 class="form-header"> Pretraži Oskarovce</h2>
    <div id="detalji"></div>
		<table>
		<tr>
                            <th>Slika:</th>
                            <th>Naziv:</th>
                            <th>Redatelj:</th>
                            <th>Godina:</th>
                            <th>Originalni naziv:</th>
                            <th>Trajanje:</th>
                            <th>Jezik:</th>		
                            <th>Glumci:</th>
                            <th>Desetljeće:</th>
                            <th>Wiki koordinate:</th>
                            <th>Akcija</th>
</tr>
		<?php
				foreach ($result as $elem) {
                $lokacija = getLocation($elem->getElementsByTagName('wiki')->item(0)->nodeValue);
				$getXML = file_get_contents($lokacija);
                $newXml = simplexml_load_string($getXML);	
				$geo_duljina = $newXml->place[0]['lon'];
				$geo_širina = $newXml->place[0]['lat'];
                $site = "https://en.wikipedia.org/wiki/" . $elem->getElementsByTagName('wiki')->item(0)->nodeValue;

		?>	
		<tr onmouseover="changeColor(this)" onmouseout="this.style.backgroundColor=''">
        <td><img src="<?php echo wikiImage($elem->getElementsByTagName('wiki')->item(0)->nodeValue); ?>" alt = "nema" width="200" /></td>
		<td> <?php echo $elem->getElementsByTagName('naziv')->item(0)->nodeValue; ?> </td>
        <td> <?php echo $elem->getElementsByTagName('ime_redatelj')->item(0)->nodeValue; ?> </td>
        <td> <?php echo $elem->getElementsByTagName('godina')->item(0)->nodeValue; ?> </td>
        <td> <?php echo $elem->getElementsByTagName('originalni_naziv')->item(0)->nodeValue; ?></td>
        <td> <?php echo $elem->getElementsByTagName('trajanje')->item(0)->nodeValue; ?> </td>	
        <td> <?php 
            $link = $elem->getElementsByTagName('jezik');
            for ($i = 0; $i <$link->length; $i++) {
            echo $elem->getElementsByTagName('ime_jezika')->item($i)->nodeValue;
            echo '<br/>';} ?> </td>
            <td> <?php echo getActors($elem->getElementsByTagName('imdb')->item(0)->nodeValue); ?> </td>
        <td>  <?php echo $elem->getAttribute('desetljece'); ?> </td>
        <td><?php echo getCoordinates($elem->getElementsByTagName('wiki')->item(0)->nodeValue);?></td>
        <td>
		<?php $url = "http://localhost/lab3//detalji.php?id="."".$elem->getAttribute("id")."&show=simple";  

				?>
					<a href="#" onclick="showDetails('<?php echo $url  ?>'); 
                    mapMe('<?php echo $geo_širina ?>', '<?php echo $geo_duljina ?>', '<?php  echo $elem->getElementsByTagName('naziv')->item(0)->nodeValue; ?>', '<?php echo $site ?>');">Više</a>
	
							<br />
						</td>	


		</tr>
        		<?php
				}
			?>	
		</table>
		</div>
        <div id="mapid"></div>
 <style>#mapid { height: 220px;}
</style>
		<script> var mymap = L.map('mapid').setView([51.505, -0.09], 13);
		L.tileLayer('http://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="http://www.opencyclemap.org">OpenCycleMap</a>, &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	}).addTo(mymap);
	</script>
        <div class="footer">
        <h4>Made by Leon Kranjčević</h4>
    </div>
	</body>
</html>