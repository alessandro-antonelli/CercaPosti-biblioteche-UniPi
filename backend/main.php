<?php
$BaseUrlSBA = 'http://www.sba.unipi.it/it/biblioteche/polo-'; // continua con:  "1/agraria"
$UrlBaseGraficiPosti = 'https://qrbiblio.unipi.it/Home/Chart?IdCat=';

$biblioteche = [
    'AGR' =>
    [   'NomeBreve' => "Agraria",
        'NomeCompleto' => "Agraria",
        'polo' => 1,
        'FineUrl' => 'agraria',
        'IDGraficoPosti' => "10a1ea41-35bc-4ec6-81af-e39cabefa260",
    ],
    'ECO' =>
    [
        'NomeBreve' => "Economia",
        'NomeCompleto' => "Economia",
        'polo' => 1,
        'FineUrl' => 'economia',
        'IDGraficoPosti' => null,
    ],
    'VET' =>
    [
        'NomeBreve' => "Veterinaria",
        'NomeCompleto' => "Medicina veterinaria",
        'polo' => 1,
        'FineUrl' => 'medicina-veterinaria',
        'IDGraficoPosti' => "8a1e70ec-7997-4097-873c-084dd8414a38",
    ],
    'GIU' =>
    [
        'NomeBreve' => "Giurisprudenza",
        'NomeCompleto' => "Giurisprudenza e Scienze politiche",
        'polo' => 2,
        'FineUrl' => 'giurisprudenza',
        'IDGraficoPosti' => "0d63f72e-4404-4f4d-bead-5bfbdee7b5b4",
    ],
    'CHI' =>
    [
        'NomeBreve' => "Chimica",
        'NomeCompleto' => "Chimica",
        'polo' => 3,
        'FineUrl' => 'chimica',
        'IDGraficoPosti' => "e4c5db0e-aec8-422d-a2d8-e2b01bfc1da7",
    ],
    'MIF' =>
    [
        'NomeBreve' => "Matematica",
        'NomeCompleto' => "Matematica, Informatica, Fisica",
        'polo' => 3,
        'FineUrl' => 'matematica-informatica-fisica',
        'IDGraficoPosti' => "c7b7fb0e-3175-4e45-a301-bcf8e2dfcc93",
    ],
    'SNA' =>
    [
        'NomeBreve' => "Biologia",
        'NomeCompleto' => "Scienze naturali e ambientali",
        'polo' => 3,
        'FineUrl' => 'scienze-naturali-e-ambientali',
        'IDGraficoPosti' => "39b31a93-86b4-4928-a94f-890b11c317a7",
    ],
    'MED' =>
    [
        'NomeBreve' => "Medicina",
        'NomeCompleto' => "Medicina e chirurgia, Farmacia",
        'polo' => 4,
        'FineUrl' => 'medicina-e-chirurgia-farmacia',
        'IDGraficoPosti' => "d334ad11-584b-4b87-be13-322463367eaa",
    ],
    'ING' =>
    [
        'NomeBreve' => "Ingegneria",
        'NomeCompleto' => "Ingegneria",
        'polo' => 5,
        'FineUrl' => 'ingegneria',
        'IDGraficoPosti' => "a96d84ba-46e8-47a1-b947-ab98a8746d6f",
    ],
    'ANG' =>
    [
        'NomeBreve' => "Anglistica",
        'NomeCompleto' => "Anglistica",
        'polo' => 6,
        'FineUrl' => 'anglistica',
        'IDGraficoPosti' => "697b9891-2510-4813-a12d-b8e0b34923d8",
    ],
    'ANT' =>
    [
        'NomeBreve' => "Antichistica",
        'NomeCompleto' => "Antichistica, linguistica, germanistica",
        'polo' => 6,
        'FineUrl' => 'antichistica-linguistica-germanistica-slavistica',
        'IDGraficoPosti' => "4c346722-ad75-4c46-a4f1-6184abfa3b44",
    ],
    'FIL' =>
    [
        'NomeBreve' => "Filosofia",
        'NomeCompleto' => "Filosofia e storia",
        'polo' => 6,
        'FineUrl' => 'filosofia-e-storia',
        'IDGraficoPosti' => "899b1b6f-80f3-4969-b0e2-aedbde979a09",
    ],
    'ITA' =>
    [
        'NomeBreve' => "Italianistica",
        'NomeCompleto' => "Italianistica e romanistica",
        'polo' => 6,
        'FineUrl' => 'italianistica-romanistica',
        'IDGraficoPosti' => "49594563-6361-4304-8992-1e75d97da7f7",
    ],
    'ART' =>
    [
        'NomeBreve' => "Arte",
        'NomeCompleto' => "Storia delle arti",
        'polo' => 6,
        'FineUrl' => 'storia-delle-arti',
        'IDGraficoPosti' => "ce5e3891-70cd-4998-add2-4441f46d83c8",
    ],
];

Echo("  <html>
        <head></head>
        <body>
            <h1>CercaPosti biblioteche UniPi</h1>");

foreach ($biblioteche as $sigla => $biblioteca)
{
    Echo("<h2>" . $biblioteca['NomeCompleto'] . "</h2>");
    ScaricaDatiSBA($biblioteca);
    ScaricaDatiBiblioteca($biblioteca);
    Echo('<hr>');
}

/********************* SCRIVO JSON ********************/
/*
$posts = array();
$posts[] = array('title'=> $title, 'url'=> $url);

$fp = fopen('dati.json', 'w');
fwrite($fp, json_encode($posts));
fclose($fp);
*/

Echo("</body></html>");

function ScaricaDatiSBA($biblioteca)
{
    if($biblioteca === null) return;
    if($biblioteca['polo'] === null || $biblioteca['FineUrl'] === null) return;
    
    global $BaseUrlSBA;
    $url = $BaseUrlSBA . $biblioteca['polo'] . '/' . $biblioteca['FineUrl'] . '/';
    Echo('<p><a href="' . $url . '">Sito</a></p>');
    
    /********************* SCARICO SORGENTE PAGINA ********************/
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
    
    $fh = fopen($url, 'r') or console_log("Errore nel download del codice sorgente: " . print_r(error_get_last(),true));
    if($fh === false)
    {
        echo("<p>Errore nel download del codice sorgente!</p>");
        return;
    }
    
    $sorgente = '';
    while (! feof($fh))
    {
        $sorgente .= fread($fh, 1);
    }
    fclose($fh);
    
    /********************* ESTRAPOLO DATI DAL SORGENTE ********************/
    libxml_use_internal_errors(true);
    $pagina = new DOMDocument;
    $pagina->loadHTML($sorgente);
    if($pagina === false) return;
    
    $xpath = new DOMXpath($pagina);
    
    // Indirizzo testuale
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/div[1]/div/div/p');
    if (ElementoTrovato($elementi)) echo "<p>Indirizzo: " . $elementi[0]->textContent . "</p>";
    
    // Telefono
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/section[1]/div/div');
    if (ElementoTrovato($elementi)) echo "<p>Telefono: " . $elementi[0]->textContent . "</p>";
    
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/div[2]/div/div/a');
    if (ElementoTrovato($elementi))
    {
        $UrlMappa = $elementi[0]->attributes->getNamedItem('href')->textContent;
        echo "<p>URL mappa: " . $UrlMappa . "</p>";
        
        if(strpos($UrlMappa, '@') === false) $UrlMappa = getRedirectUrl($UrlMappa);
        
        if(($InizioCoord = strpos($UrlMappa, '@')) !== false)
        {
            $DivisoreLatLong = strpos($UrlMappa, ',', $InizioCoord + 1);
            $FineCoord = strpos($UrlMappa, ',', $DivisoreLatLong + 1);
            $lat = substr($UrlMappa, $InizioCoord + 1, $DivisoreLatLong - $InizioCoord - 1);
            $long = substr($UrlMappa, $DivisoreLatLong + 1, $FineCoord - $DivisoreLatLong - 1);
            echo('<p>Latitudine: ' . $lat . '</p>');
            echo('<p>Longitudine: ' . $long . '</p>');
        }
    }
    
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/section[2]/div/div');
    if (ElementoTrovato($elementi)) echo "<p>Fax: " . $elementi[0]->textContent. "</p>";
    
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/section[3]/div/div/a');
    if (ElementoTrovato($elementi)) echo "<p>Email: " . $elementi[0]->textContent. "</p>";
    
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/section[1]/div/div');
    if (ElementoTrovato($elementi)) echo "<p>Posti pre-covid: " . $elementi[0]->textContent. "</p>";
    
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/section[2]/div/div');
    if (ElementoTrovato($elementi)) echo "<p>Posti post-covid: " . $elementi[0]->textContent. "</p>";
    
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/section[3]/div/div');
    if (ElementoTrovato($elementi)) echo "<p>Aria condizionata: " . $elementi[0]->textContent. "</p>";
    
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[2]/div/section/div/div/iframe');
    if (ElementoTrovato($elementi))
    {
        $UrlIncorporazione = $elementi[0]->attributes->getNamedItem('src')->textContent;
        $PosInizioUrlPagina = strpos($UrlIncorporazione, 'href=') + 5;
        $PosFineUrlPagina = strpos($UrlIncorporazione, '&', $PosInizioUrlPagina);
        $UrlPagina = substr($UrlIncorporazione, $PosInizioUrlPagina, $PosFineUrlPagina - $PosInizioUrlPagina);
        echo '<p>Pagina Facebook: <a href="' . urldecode($UrlPagina) . '">' . urldecode($UrlPagina) . '</a></p>';
    }

    $elementiAvvisiUrgenti = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/section/div/div/div/span/a');
    $elementiAvvisiNormali = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/section/div/div/div/a');
    if (ElementoTrovato($elementiAvvisiUrgenti) || ElementoTrovato($elementiAvvisiNormali))
    {
        echo "<p>Avvisi:</p>" . "</p><ul>";
        foreach($elementiAvvisiUrgenti as $elemento)
        {
            echo '<li style="font-weight: bold"><a href="https://www.sba.unipi.it' . $elemento->attributes->getNamedItem("href")->textContent . '">' . $elemento->textContent . "</a></li>";
        }
        foreach($elementiAvvisiNormali as $elemento)
        {
            echo '<li><a href="https://www.sba.unipi.it' . $elemento->attributes->getNamedItem("href")->textContent . '">' . $elemento->textContent . "</a></li>";
        }
        echo("</ul></p>");
    }
    

    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/section[5]/div/div/div');
    if (ElementoTrovato($elementi))
    {
        echo "<p>Node ID A: " . $elementi[0]->attributes->getNamedItem('data-nid')->textContent. "</p>";
    }
    
      
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article');
    if (ElementoTrovato($elementi))
    {
        $IdElemento = $elementi[0]->attributes->getNamedItem('id')->textContent;
        $NID = substr($IdElemento, 5, strlen($IdElemento) - 5);
        echo "<p>Node ID B: " . $NID;
        echo ' <a href="https://www.sba.unipi.it/it/opening_hours/instances?nid=' . $NID . '&from_date=2022-02-10&to_date=2022-02-15">URL</a></p>';
    }
    
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/section[4]/div/div/span/a');
    if (ElementoTrovato($elementi))
    {
        echo("<p>Piantina con collocazioni e servizi (PDF):</p><ul>");
        foreach($elementi as $elemento)
        {
            echo('<li><a href="' . $elemento->attributes->getNamedItem('href')->textContent . '">' . $elemento->textContent . '</a></li>');
        }
        echo("</ul>");
    }
    
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[4]/div/figure/a');
    if (ElementoTrovato($elementi))
    {
        echo("<p>Piantina con collocazioni e servizi (PNG):</p><ul>");
        foreach($elementi as $elemento)
        {
            $UrlIMG = $elemento->attributes->getNamedItem('href')->textContent;
            $NomeFileIMG = basename($UrlIMG);
            echo('<li><a href="' . $UrlIMG . '">' . $NomeFileIMG . '</a></li>');
        }
        echo("</ul>");
    }
}

function ElementoTrovato($ArrayRisultati)
{
    if($ArrayRisultati === null || $ArrayRisultati === false ||
        count($ArrayRisultati) <= 0 || $ArrayRisultati[0] === null) return false;
    else return true;
}

function getRedirectUrl($url)
{
    stream_context_set_default(array(
        'http' => array(
            'method' => 'HEAD'
        )
    ));
    $headers = get_headers($url, 1);
    if ($headers !== false && isset($headers['Location'])) {
        return is_array($headers['Location']) ? array_pop($headers['Location']) : $headers['Location'];
    }
    return false;
}

function ScaricaDatiBiblioteca($biblioteca)
{
    if($biblioteca === null) return;
    
    if($biblioteca['IDGraficoPosti'] === null)
    {
        Echo("Dati di occupazione dei posti non disponibili");
        return;
    }
    
    global $UrlBaseGraficiPosti;
    Echo('<p><a href="' . $UrlBaseGraficiPosti . $biblioteca['IDGraficoPosti'] . '">Occupazione posti</a></p>');

    /********************* SCARICO SORGENTE PAGINA ********************/
    $fh = fopen($UrlBaseGraficiPosti . $biblioteca['IDGraficoPosti'], 'r') or console_log("Errore nel download del codice sorgente");
    if($fh === false)
    {
        echo("Errore nel download del codice sorgente!");
        return;
    }
    
    $sorgente = '';
    while (! feof($fh))
    {
        //$LunghDaLeggere = min([0 => 1048576, 1 => strlen($sorgente)]);
        $sorgente .= fread($fh, 1);
    }
    fclose($fh);
    
    /********************* ESTRAPOLO DATI DAL SORGENTE ********************/
    
    // devo trovare nel sorgente la riga: «var yValues = ["11","0"];»
    // dove 11 sono i posti liberi, e 0 sono i posti occupati
    $PosInizioDati = strpos($sorgente, "var yValues");
    if($PosInizioDati === false)
    {
        console_log("Errore nel parsing del codice sorgente: dati sull'occupazione dei posti non trovati!");
    } else
    {
        $PosInizioLiberi = strpos($sorgente, '"', $PosInizioDati) + 1;
        $PosFineLiberi = strpos($sorgente, '"', $PosInizioLiberi);
        $PostiLiberi = substr($sorgente, $PosInizioLiberi, $PosFineLiberi - $PosInizioLiberi);
        Echo("<p>Liberi = " . $PostiLiberi . "</p>");
        
        $PosInizioOccupati = strpos($sorgente, '"', $PosFineLiberi + 1) + 1;
        $PosFineOccupati = strpos($sorgente, '"', $PosInizioOccupati);
        $PostiOccupati = substr($sorgente, $PosInizioOccupati, $PosFineOccupati - $PosInizioOccupati);
        Echo("<p>Occupati = " . $PostiOccupati . "</p>");
        
        $PostiTotali = $PostiOccupati + $PostiLiberi;
        Echo("<p>Saldo = " . $PostiTotali . "</p>");
        
        $PosInizioCapienzaDichiarata = strpos($sorgente, 'var sottoTitolo = "Posti Totali: ') + 33;
        $PosFineCapienzaDichiarata = strpos($sorgente, '"', $PosInizioCapienzaDichiarata);
        $CapienzaDichiarata = substr($sorgente, $PosInizioCapienzaDichiarata, $PosFineCapienzaDichiarata - $PosInizioCapienzaDichiarata);
        Echo("<p>Capienza dichiarata = " . $CapienzaDichiarata . "</p>");
    }
}

function console_log( $data )
{
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
}
?>