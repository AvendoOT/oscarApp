<?php
header('Access-Control-Allow-Origin: *');
include ("funkcije.php");

$kod = $_GET["id"];

$dom = new DOMDocument();
  
$dom->load('podaci.xml'); 

$xp = new DOMXPath($dom); 

$putanja = '/filmovi/film[contains(@id, "'. $kod . '")]';


$rez = $xp->query($putanja);



foreach($rez as $elem){

	if(($kod) == ($elem->getAttribute('id'))) {
		
			echo "<b>Dodatni detalji:</b><br/>";
			echo "<b>Sinopsis: </b>";
			echo $elem->getElementsByTagName('sinopsis')->item(0)->nodeValue; 
			echo "<br/><b> Wikipedia sinopsis: </b>";
			echo getParagraph($elem->getElementsByTagName('wiki')->item(0)->nodeValue);
			if (!empty(getBoxOffice($elem->getElementsByTagName('imdb')->item(0)->nodeValue))) {
				echo "<br/><b> Box office: </b>";
			echo getBoxOffice($elem->getElementsByTagName('imdb')->item(0)->nodeValue);
			}
			
			echo "<br/>";
		
	}
}
sleep(2);

?>