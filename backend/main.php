<?php
$NomiBiblioteche = array();
$URLHome = array();
$URLGraficiPosti = array();

Echo("  <html>
        <head></head>
        <body>
            <h1>CercaPosti biblioteche UniPi</h1>");

/********************* SCARICO LISTA BIBLIOTECHE ********************/
$fh = fopen("http://www.sba.unipi.it/", 'r'); //or AvvisaEdEsci("Errore nel download della lista biblioteche: " . print_r(error_get_last(),true));
if($fh === false) AvvisaEdEsci("Errore nel download della lista biblioteche: " . implode("|", $http_response_header));

$sorgente = '';
while (! feof($fh))
{
    $sorgente .= fread($fh, 1);
}
fclose($fh);

/********************* ESTRAPOLO URL BIBLIOTECHE DAL SORGENTE ********************/
libxml_use_internal_errors(true);
$pagina = new DOMDocument;
$pagina->loadHTML($sorgente);
if($pagina === false) return;

$xpath = new DOMXpath($pagina);

$elementi = $xpath->query('/html/body/div[4]/div/div[1]/nav/div/div/ul/li[5]/div/div/div/div/div/ul/li/div/div/div/div/div/ul/li/a');
if (ElementoTrovato($elementi))
{
    foreach($elementi as $elemento)
    {
        $UrlCompleto = "http://www.sba.unipi.it" . $elemento->attributes->getNamedItem('href')->textContent;
        array_push($URLHome, $UrlCompleto);
        array_push($NomiBiblioteche, $elemento->textContent);
    }
}
$NomiBiblioteche = array_reverse($NomiBiblioteche);

/********************* SCARICO DATI BIBLIOTECHE ********************/
foreach($URLHome as $URLBiblioteca)
{
    Echo("<h2>" . array_pop($NomiBiblioteche) . "</h2>");
    Echo('<p><a href="' . $URLBiblioteca . '">Sito</a></p>');
    ScaricaDatiSBA($URLBiblioteca);
    Echo('<hr>');
}

/********************* SCRIVO JSON ********************/
/*
                TODO           
$posts = array();
$posts[] = array('title'=> $title, 'url'=> $url);

$fp = fopen('dati.json', 'w');
fwrite($fp, json_encode($posts));
fclose($fp);
*/

Echo("</body></html>");

function ScaricaDatiSBA($URLbiblioteca)
{
    if($URLbiblioteca === null) AvvisaEdEsci("Informazioni sulla biblioteca non disponibili: " . $URLbiblioteca);
    
    /********************* SCARICO SORGENTE PAGINA ********************/
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
    
    $OpzioniHttp = array('http' =>
        array(
            'method' => 'GET',
            'max_redirects' => '5',
            'ignore_errors' => '1'
        )
    );
    $context = stream_context_create($OpzioniHttp);
    
    //sleep(500);
    $fh = fopen($URLbiblioteca, 'r', false, $context); // or AvvisaEdEsci("Errore nel download delle informazioni sulla biblioteca: impossibile aprire il file descriptor! " . print_r(error_get_last(),true));
    if($fh === false) AvvisaEdEsci("Errore nel download delle informazioni sulla biblioteca: impossibile aprire il file descriptor! " . implode("|", $http_response_header));

    if(feof($fh)) AvvisaEdEsci("Errore nel download delle informazioni sulla biblioteca: il file descriptor contiene solo EOF!");
    
    $sorgente = '';
    while (! feof($fh))
    {
        $sorgente .= fread($fh, 1);
    }
    fclose($fh);
    if(strlen($sorgente) == 0) AvvisaEdEsci("Errore nel download delle informazioni sulla biblioteca: il codice sorgente è vuoto!");
    
    /********************* ESTRAPOLO DATI DAL SORGENTE ********************/
    libxml_use_internal_errors(true);
    $pagina = new DOMDocument;
    $pagina->loadHTML($sorgente);
    if($pagina === false) return;
    
    $xpath = new DOMXpath($pagina);
    
    // Nome
    //$elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/header/h1');
    //if (ElementoTrovato($elementi)) Echo("<h2>" . $elementi[0]->textContent . "</h2>");
    
    // URL grafico posti disponibili
    global $URLGraficiPosti;
    $UrlOccupazionePosti = null;
    $elementi = $xpath->query('//*[@id="iframe-field-room-entrance-0"]');
    if (ElementoTrovato($elementi))
    {
        $UrlOccupazionePosti = $elementi[0]->attributes->getNamedItem('src')->textContent;
        array_push($URLGraficiPosti, $UrlOccupazionePosti);
        echo "<p>URL grafico posti: " . $UrlOccupazionePosti . "</p>";
    } else echo '<p style="color: red">Url occupazione posti non trovato</p>';
    
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
    

    $elementi = $xpath->query('//article/div/section[5]/div/div/div');
    if (ElementoTrovato($elementi))
    {
        echo '<p style="background-color: yellow">Trovato Node ID A! :) :)</p>';
        echo '<p style="background-color: yellow">Node ID A: ' . $elementi[0]->attributes->getNamedItem('data-nid')->textContent. "</p>";
    } else echo '<p style="color: red">Impossibile trovare elemento Node ID A!</p>';
    
      
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
    
    if($UrlOccupazionePosti !== null) ScaricaDatiOccupazionePosti($UrlOccupazionePosti);
    else Echo("Dati di occupazione dei posti non disponibili");
}

function ScaricaDatiOccupazionePosti($URLgrafico)
{
    if($URLgrafico === null)
    {
        Echo("Dati di occupazione dei posti non disponibili");
        return;
    }
    
    
    Echo('<p><a href="' . $URLgrafico . '">Occupazione posti</a></p>');

    /********************* SCARICO SORGENTE PAGINA ********************/
    $OpzioniHttp = array('http' =>
        array(
            'method' => 'GET',
            'max_redirects' => '5',
            'ignore_errors' => '1'
        )
    );
    $context = stream_context_create($OpzioniHttp);
    
    //sleep(500);
    $fh = fopen($URLgrafico, 'r', false, $context);
    if($fh === false) AvvisaEdEsci("Errore nel download dei dati di occupazione dei posti! " . implode("|", $http_response_header));
    
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
        AvvisaEdEsci("Errore nel parsing dei dati di occupazione dei posti: numeri dei posti non trovati!");
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

function AvvisaEdEsci($testo)
{
    echo '<p style="font-weight: bold; color: red">' . $testo . '</p>';
    exit();
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
?>