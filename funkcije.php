<?php

  function queryGenerator()
  {
    $queryArray[] = elementExistsFilter("naziv");
    $queryArray[] = elementExistsFilter("godina");
    $queryArray[] = elementExistsFilter("originalni_naziv");
    $queryArray[] = elementExistsFilter("sinopsis");
	  $queryArray[] = attributeEqualsFilter("desetljece");
    $queryArray[] = attributeEqualsFilter("balkanski_film");
    $queryArray[] = connector('redatelj','ime_redatelj', 'ime_redatelj');
	  $queryArray[] = connector('jezik','ime_jezika', 'ime_jezik');
    $queryArray[] = getBetweenDuration("trajanje_start", "trajanje_end");
    $queryArray[] = getBetweenDecade("desetljeceStart", "desetljeceEnd");
    $queryArray = array_remove_empty($queryArray);
    $query = implode(" and ", $queryArray);

    if (empty($query))
      return "/filmovi/film";

    $query = "/filmovi/film[" . $query . "]";
    return $query;
  }

  function attributesExistsFilter($attributeName)
  {
    if (!empty($_REQUEST[$attributeName]))
    {
      foreach ($_REQUEST[$attributeName] as $attribute)
      {
        $queryFragment[] = "@" . $attributeName . "='" . $attribute . "'";
      }
      return "(" . implode(" or ", $queryFragment) . ")";
    }
    else
      return "";
  }

  function elementsExistsFilter($elementName)
  {
    if (!empty($_REQUEST[$elementName]))
    {
      foreach ($_REQUEST[$elementName] as $element)
      {
        $queryFragment[] = $elementName . "='" . $element . "'";
      }
      return "(" . implode(" or ", $queryFragment) . ")";
    }
    else
      return "";
  }

  function attributeExistsFilter($attributeName)
  {
    if(!empty($_REQUEST[$attributeName]))
      return "contains(@" . $attributeName . ",'" . $_REQUEST[$attributeName] . "')";
    return "";
  }

function attributeEqualsFilter($attributeName)
  {
    if(!empty($_REQUEST[$attributeName]))
      return "@" . $attributeName . "='" . $_REQUEST[$attributeName] . "'";
    return "";
  }

  function elementExistsFilter($elementName)
  {
    if(!empty($_REQUEST[$elementName]))
      return "contains(" . $elementName . ",'" . $_REQUEST[$elementName] . "')";
    return "";
  }

  function getElementValue($node, $elementName)
  {
    return $node->getElementsByTagName($elementName)->item(0);
  }

  function connector($parentElementName, $childElementName, $formElementName)
  {
    if (!empty($_REQUEST[$formElementName]))
    {
      return $parentElementName . "[contains(" . $childElementName . ", '" . $_REQUEST[$formElementName] . "')]";
    }
  }

  function array_remove_empty($arr)
  {
    $narr = array();
    foreach($arr as $key => $val) {
        if (is_array($val))
      {
        $val = array_remove_empty($val);
        if (count($val)!=0)
        {
          $narr[$key] = $val;
        }
      }
      else
      {
        if (trim($val) != "")
        {
          $narr[$key] = $val;
        }
      }
    }
    unset($arr);
    return $narr;
  }

  function getBetweenDuration($start, $end) {
      $xmlDoc = new DOMDocument();
      $xmlDoc->load("podaci.xml");
      $root = $xmlDoc->documentElement;
      $title = $root->getElementsByTagName("trajanje");
    if (!empty($_REQUEST[$start]) && !empty($_REQUEST[$end]))
    {
      foreach($title as $element) {
        if ($element->nodeValue >= $_REQUEST[$start] &&  $element->nodeValue <= $_REQUEST[$end]) {
          $queryFragment[] = "trajanje" . "='" .  $element->nodeValue . "'";
        }
    }
      return "(" . implode(" or ", $queryFragment) . ")";
    } else if (!empty($_REQUEST[$start]) && empty($_REQUEST[$end])) {
      foreach($title as $element) {
        if ($element->nodeValue >= $_REQUEST[$start]) {
          $queryFragment[] = "trajanje" . "='" .  $element->nodeValue . "'";
        }
    }
      return "(" . implode(" or ", $queryFragment) . ")";
    } else if (empty($_REQUEST[$start]) && !empty($_REQUEST[$end])) {
      foreach($title as $element) {
        if ($element->nodeValue <= $_REQUEST[$end]) {
          $queryFragment[] = "trajanje" . "='" .  $element->nodeValue . "'";
        }
    }
      return "(" . implode(" or ", $queryFragment) . ")";

    }
    else return "";
  }

  function getBetweenDecade($start, $end) {
    $xmlDoc = new DOMDocument();
      $xmlDoc->load("podaci.xml");
      $root = $xmlDoc->documentElement;
      $title = $root->getElementsByTagName("film");
    if (!empty($_REQUEST[$start]) && !empty($_REQUEST[$end]))
    {
      foreach($title as $element) {
        $elem = $element->getAttribute("desetljece");
        if ($elem >= $_REQUEST[$start] &&  $elem <= $_REQUEST[$end]) {
          $queryFragment[] = "@desetljece='"  .  $elem . "'";
        }
    }
    return implode(" or ", $queryFragment);
    } else if (!empty($_REQUEST[$start]) && empty($_REQUEST[$end])) {
      foreach($title as $element) {
        $elem = $element->getAttribute("desetljece");
        if ($elem >= $_REQUEST[$start]) {
          $queryFragment[] = "@desetljece='"  .  $elem . "'";
        }
    }
    return implode(" or ", $queryFragment);
    } else if (empty($_REQUEST[$start]) && !empty($_REQUEST[$end])) {
      foreach($title as $element) {
        $elem = $element->getAttribute("desetljece");
        if ($elem <= $_REQUEST[$end]) {
          $queryFragment[] = "@desetljece='"  .  $elem . "'";
        }
    }
    return implode(" or ", $queryFragment);
    }
    else return "";

  }

  function getPageId($argument) {
    $link = "https://en.wikipedia.org/api/rest_v1/page/summary/" . $argument;
    $fts = file_get_contents($link);
		$encod = utf8_encode($fts);
		$dekodirano = json_decode($encod, true);
    if (isset($dekodirano)) {
      return $dekodirano["pageid"];
    }
		
  }

  function wikiImage($argument) {
			$link = "https://en.wikipedia.org/api/rest_v1/page/summary/" .  $argument;
      $fts = file_get_contents($link);
			$encod = utf8_encode($fts);
			$dekodirano = json_decode($encod, true);
      if (isset($dekodirano["originalimage"]["source"])) {
        return strip_tags($dekodirano["originalimage"]["source"]);
      } else {
        return strip_tags("assets/oscar_head_logo.png");
      }
    
	} 

  function getParagraph($argument) {
		$link = "https://en.wikipedia.org/api/rest_v1/page/summary/" . $argument;	
    $fts = file_get_contents($link);
		$encod = utf8_encode($fts);
    $dekodirano = json_decode($encod, true);
    if (isset($dekodirano)) {
      return strip_tags($dekodirano["extract"]);
    }
	
  }

  function getCoordinates($argument) {
		$link = "https://en.wikipedia.org/api/rest_v1/page/summary/" . $argument;	
    $fts = file_get_contents($link);
		$encod = utf8_encode($fts);
		$dekodirano = json_decode($encod, true);
    if (isset($dekodirano["coordinates"])) {
      return strip_tags($dekodirano["coordinates"]);
    } else {
      return "Nema dostupnih koordinata";
    }	
  }

  function getActors($argument) {
    $link = "http://www.omdbapi.com/?i=" . $argument . "&apikey=483fe36e";
    $fts = file_get_contents($link);
		$encod = utf8_encode($fts);
		$dekodirano = json_decode($encod, true);
    if (isset($dekodirano)) {
      return strip_tags($dekodirano["Actors"]);
    }
  }

  function getBoxOffice($argument) {
    $link = "http://www.omdbapi.com/?i=" . $argument . "&apikey=483fe36e";
    $fts = file_get_contents($link);
		$encod = utf8_encode($fts);
		$dekodirano = json_decode($encod, true);
    if (isset($dekodirano)) {
      return strip_tags($dekodirano["BoxOffice"]);
    }
  }

  function getLocation($argument) {
    $link = "https://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles=" . $argument . "&format=json";
    $fts = file_get_contents($link);
		$encod = utf8_encode($fts);
		$dekodirano = json_decode($encod, true);
    if (isset($dekodirano)) {
      if (preg_match("/country\s*=\s(.*)/", $dekodirano["query"]["pages"][getPageId($argument)]["revisions"][0]["*"], $matches)) {}
    if (isset($matches[1])) {
      $pieces = explode(" ", $matches[1]);
      return strip_tags("https://nominatim.openstreetmap.org/search?q=" . rawurlencode($pieces[0]) .  "&format=xml");
    }
    }  
  } 

?>