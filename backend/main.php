<?php
$biblioteche = array();
$risposta = array();

$risposta["timestamp"] = time();

/********************* SCARICO LISTA BIBLIOTECHE ********************/
$fh = fopen("http://www.sba.unipi.it/", 'r');
if($fh === false) $risposta["esito"] = implode("; ", $http_response_header); //Errore nel download della lista biblioteche
else
{
    $risposta["esito"] = "ok";
    $sorgente = '';
    while (! feof($fh)) $sorgente .= fread($fh, 1);
    fclose($fh);

    /********************* ESTRAPOLO URL BIBLIOTECHE DAL SORGENTE e POPOLO l'array biblioteche ********************/
    $pagina = new DOMDocument;
    $pagina->loadHTML($sorgente);
    if($pagina === false) return;

    $xpath = new DOMXpath($pagina);

    $elementi = $xpath->query('/html/body/div[4]/div/div[1]/nav/div/div/ul/li[5]/div/div/div/div/div/ul/li/div/div/div/div/div/ul/li/a');
    if (ElementoTrovato($elementi))
    {
        foreach($elementi as $elemento)
        {
            $biblioteca = array();

            $NomeCompleto = $elemento->textContent;
            $biblioteca["NomeCompleto"] = trim($NomeCompleto);

            $UrlCompleto = "http://www.sba.unipi.it" . $elemento->attributes->getNamedItem('href')->textContent;
            $biblioteca["UrlSito"] = $UrlCompleto;

            /********************* SCARICO DATI BIBLIOTECHE ********************/
            ScaricaDatiBiblioteca($biblioteca, $UrlCompleto);

            $biblioteche[] = $biblioteca;
        }
    }
}

/********************* SCRIVO JSON ********************/
$risposta["biblioteche"] = $biblioteche;
echo(json_encode($risposta));



/****************************************************************************
 ****************************************************************************
 **************************************************************************** */



function ScaricaDatiBiblioteca(&$biblioteca, $URLbiblioteca)
{
    if($URLbiblioteca === null) { $biblioteca["esito"] = "l'URL del sito è vuoto"; return; } //Informazioni sulla biblioteca non disponibili
    
    /********************* SCARICO SORGENTE PAGINA ********************/
    $OpzioniHttp = array('http' =>
        array(
            'method' => 'GET',
            'max_redirects' => '5',
            'ignore_errors' => '1'
        )
    );
    $context = stream_context_create($OpzioniHttp);
    
    $fh = fopen($URLbiblioteca, 'r', false, $context);
    if($fh === false)
    {
        //Errore nel download delle informazioni sulla biblioteca: impossibile aprire il file descriptor!
        $biblioteca["esito"] = implode("|", $http_response_header);
        return;
    } else if(feof($fh))
    {
        $biblioteca["esito"] = "il file descriptor contiene solo EOF!";
        return;
    }
    
    $sorgente = '';
    while (! feof($fh)) $sorgente .= fread($fh, 1);
    fclose($fh);
    if(strlen($sorgente) == 0)
    {
        $biblioteca["esito"] = "il codice sorgente è vuoto!";
        return;
    }
    $biblioteca["esito"] = "ok";
    
    /********************* ESTRAPOLO DATI DAL SORGENTE ********************/
    $pagina = new DOMDocument;
    $pagina->loadHTML($sorgente);
    if($pagina === false) { $biblioteca["esito"] = "il codice sorgente non è un HTML valido!"; return; }
    
    $xpath = new DOMXpath($pagina);
    
    // Nome
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/header/h1');
    if (ElementoTrovato($elementi)) $biblioteca["NomeCompleto"] = trim($elementi[0]->textContent);
    
    // Indirizzo testuale
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/div[1]/div/div/p');
    if (ElementoTrovato($elementi)) $biblioteca["indirizzo"] = $elementi[0]->textContent;
    
    // Telefono
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr[1]/td/article/div/section[1]/div');
    if (ElementoTrovato($elementi)) $biblioteca["telefono"] = $elementi[0]->textContent;
    
    // Link Google Maps e coordinate
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/div[2]/div/div/a');
    if (ElementoTrovato($elementi))
    {
        $UrlMappa = $elementi[0]->attributes->getNamedItem('href')->textContent;
        $biblioteca["UrlMappa"] = $UrlMappa;
        
        if(strpos($UrlMappa, '@') === false)
        {
            if(strpos($UrlMappa, '/?q=') !== false) $UrlMappa = str_replace('/?q=', '/maps/place/', $UrlMappa);
            while(SeguiRedirectHTTP($UrlMappa) !== false) $UrlMappa = SeguiRedirectHTTP($UrlMappa);
        }
        
        if(($InizioCoord = strpos($UrlMappa, '@')) !== false)
        {
            $DivisoreLatLong = strpos($UrlMappa, ',', $InizioCoord + 1);
            $FineCoord = strpos($UrlMappa, ',', $DivisoreLatLong + 1);
            $lat = substr($UrlMappa, $InizioCoord + 1, $DivisoreLatLong - $InizioCoord - 1);
            $long = substr($UrlMappa, $DivisoreLatLong + 1, $FineCoord - $DivisoreLatLong - 1);
            $biblioteca["latitudine"] = $lat;
            $biblioteca["longitudine"] = $long;
        }
    }
    
    // Fax
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/section[2]/div/div');
    if (ElementoTrovato($elementi)) $biblioteca["fax"] = $elementi[0]->textContent;
    
    // Email
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article/div/section[3]/div/div/a');
    if (ElementoTrovato($elementi)) $biblioteca["email"] = $elementi[0]->textContent;
    
    // Capienza e aria condizionata
    $sezioni = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/section');
    if (ElementoTrovato($sezioni))
    {
        foreach($sezioni as $sezione)
        {
            $titolo = $xpath->query('h2', $sezione);
            
            if(ElementoTrovato($titolo))
            {
                $TestoTitolo = $titolo[0]->textContent;
                $TestoTitolo = substr($TestoTitolo, 0, strlen($TestoTitolo) - 2);
                $contenuto = $xpath->query('div', $sezione);
                
                if(strcmp($TestoTitolo,'Posti di lettura:') == 0 && ElementoTrovato($contenuto))
                {
                    $biblioteca["CapienzaPreCovid"] = intval($contenuto[0]->textContent);
                }
                else if(strcmp($TestoTitolo,'Posti di lettura durante il Covid:') == 0 && ElementoTrovato($contenuto))
                {
                    $biblioteca["CapienzaPostCovid"] = intval($contenuto[0]->textContent);
                }
                else if(strcmp($TestoTitolo,'Aria condizionata:') == 0 && ElementoTrovato($contenuto))
                {
                    $biblioteca["AriaCondizionata"] = $contenuto[0]->textContent;
                }
                else if(strcmp($TestoTitolo,'Dipartimenti afferenti:') == 0 && ElementoTrovato($contenuto))
                {
                    $biblioteca["DipartimentiAfferenti"] = $contenuto[0]->textContent;
                }
            }
        }
    }
    
    // Pagina Facebook
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[2]/div/section/div/div/iframe');
    if (ElementoTrovato($elementi))
    {
        $UrlIncorporazione = $elementi[0]->attributes->getNamedItem('src')->textContent;
        $PosInizioUrlPagina = strpos($UrlIncorporazione, 'href=') + 5;
        $PosFineUrlPagina = strpos($UrlIncorporazione, '&', $PosInizioUrlPagina);
        $UrlPagina = substr($UrlIncorporazione, $PosInizioUrlPagina, $PosFineUrlPagina - $PosInizioUrlPagina);
        $biblioteca["UrlFacebook"] = urldecode($UrlPagina);
    }

    // Avvisi
    $elementiAvvisiUrgenti = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/section/div/div/div/span/a');
    $elementiAvvisiNormali = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/section/div/div/div/a');
    if (ElementoTrovato($elementiAvvisiUrgenti) || ElementoTrovato($elementiAvvisiNormali))
    {
        $avvisi = array();
        foreach($elementiAvvisiUrgenti as $elemento)
        {
            $avvisi[] = array('url' => "https://www.sba.unipi.it" . $elemento->attributes->getNamedItem("href")->textContent,
                                    'titolo' => $elemento->textContent,
                                    'urgente' => true);
        }
        foreach($elementiAvvisiNormali as $elemento)
        {
            $avvisi[] = array('url' => "https://www.sba.unipi.it" . $elemento->attributes->getNamedItem("href")->textContent,
                                    'titolo' => $elemento->textContent,
                                    'urgente' => false);
        }
        $biblioteca["avvisi"] = $avvisi;
    }
    
    // Piantina PDF
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/section[4]/div/div/span/a');
    if (ElementoTrovato($elementi))
    {
        $MappePDF = array(); //Piantina con collocazioni e servizi
        foreach($elementi as $elemento)
        {
            $MappePDF[] = array('url' => $elemento->attributes->getNamedItem('href')->textContent,
                                'nome' => $elemento->textContent);
        }
        $biblioteca["MappePDF"] = $MappePDF;
    }
    
    // Piantina PNG
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[4]/div/figure/a');
    if (ElementoTrovato($elementi))
    {
        $MappePNG = array();
        foreach($elementi as $elemento)
        {
            $UrlIMG = $elemento->attributes->getNamedItem('href')->textContent;
            $NomeFileIMG = basename($UrlIMG);
            $MappePNG[] = array('url' => $UrlIMG, 'nome' => $NomeFileIMG);
        }
        $biblioteca["MappePNG"] = $MappePNG;
    }

    // ID webapp orario di apertura
    $NID = null;
    $elementi = $xpath->query('/html/body/div[4]/div/div[3]/div[1]/div/section/div/div/article/div/div[2]/div/div/div/div/table/tbody/tr/td/article');
    if (ElementoTrovato($elementi))
    {
        $IdElemento = $elementi[0]->attributes->getNamedItem('id')->textContent;
        $NID = substr($IdElemento, 5, strlen($IdElemento) - 5);
    } else
    {
        $inizioNID = strpos($sorgente, 'data-nid="');
        if($inizioNID !== false)
        {
            $inizioNID = $inizioNID + 10;
            $fineNID = strpos($sorgente, '"', $inizioNID);
            $NID = substr($sorgente, $inizioNID, $fineNID - $inizioNID);
        }
    }
    if($NID !== null)
    {
        $biblioteca["IDorario"] = $NID; //Node ID

        //le date devono essere in formato ISO: esempio 2022-01-31
        $DataOggi = date("Y-m-d");
        $DataDopoDomani = date("Y-m-d", strtotime($DataOggi . ' + 2 days'));
        $UrlOrario = 'http://www.sba.unipi.it/it/opening_hours/instances?nid=' . $NID . '&from_date=' . $DataOggi . '&to_date=' . $DataDopoDomani;

        $ArrayAperture = ScaricaDatiOrario($UrlOrario);
        if($ArrayAperture !== null) $biblioteca["OrarioApertura"] = $ArrayAperture;
    }

    // URL grafico posti disponibili
    $elementi = $xpath->query('//*[@id="iframe-field-room-entrance-0"]');
    if (ElementoTrovato($elementi))
    {
        $UrlOccupazionePosti = $elementi[0]->attributes->getNamedItem('src')->textContent;
        $biblioteca["UrlOccupazionePosti"] = $UrlOccupazionePosti;
        
        $ArrayPosti = ScaricaDatiOccupazionePosti($UrlOccupazionePosti);
        $biblioteca["StatoOccupazionePosti"] = $ArrayPosti;
    }
}

function ScaricaDatiOrario($UrlOrario)
{
    $risultato = array();
    $risultato["timestamp"] = time();
    $risultato["url"] = $UrlOrario;
    if($UrlOrario === null)
    {
        $risultato["esito"] = "URL vuoto";
        return $risultato;
    }

    /********************* SCARICO SORGENTE PAGINA ********************/
    $OpzioniHttp = array('http' =>
        array(
            'method' => 'GET',
            'max_redirects' => '5',
            'ignore_errors' => '1'
        )
    );
    $context = stream_context_create($OpzioniHttp);

    $fh = fopen($UrlOrario, 'r', false, $context);
    if($fh === false) { $risultato["esito"] = implode("; ", $http_response_header); return $risultato; }
    
    $sorgente = '';
    while (! feof($fh)) $sorgente .= fread($fh, 1);
    fclose($fh);
    if($sorgente === null || strlen($sorgente) == 0) $risultato["esito"] = "ricevuta risposta vuota!";
    else
    {   
        $array = json_decode($sorgente);
        if($array === null) $risultato["esito"] = "Ricevuta risposta non JSON: " . $sorgente;
        else
        {
            $risultato["esito"] = "ok";
            $risultato["aperture"] = $array;
        }
    }
    return $risultato;
}

function ScaricaDatiOccupazionePosti($URLgrafico)
{
    $risultato = array();
    $risultato["timestamp"] = time();
    if($URLgrafico === null)
    {
        $risultato["esito"] = "URL vuoto"; //"Dati di occupazione dei posti non disponibili"
        return risultato;
    }

    /********************* SCARICO SORGENTE PAGINA ********************/
    $OpzioniHttp = array('http' =>
        array(
            'method' => 'GET',
            'max_redirects' => '5',
            'ignore_errors' => '1'
        )
    );
    $context = stream_context_create($OpzioniHttp);
    
    $fh = fopen($URLgrafico, 'r', false, $context);
    if($fh === false) { $risultato["esito"] = implode("; ", $http_response_header); return risultato; }
    
    $sorgente = '';
    while (! feof($fh)) $sorgente .= fread($fh, 1);
    fclose($fh);
    
    /********************* ESTRAPOLO DATI DAL SORGENTE ********************/
    
    // Devo trovare nel sorgente la riga: «var yValues = ["11","0"];»
    // dove 11 sono i posti liberi, e 0 sono i posti occupati
    $PosInizioDati = strpos($sorgente, "var yValues");
    if($PosInizioDati === false)
    {
        //Errore nel parsing dei dati di occupazione dei posti
        $risultato["esito"] = implode("; ", "numeri dei posti non trovati nel sorgente!");
        return risultato;
    } else
    {
        $risultato["esito"] = "ok";

        $PosInizioLiberi = strpos($sorgente, '"', $PosInizioDati) + 1;
        $PosFineLiberi = strpos($sorgente, '"', $PosInizioLiberi);
        $PostiLiberi = intval(substr($sorgente, $PosInizioLiberi, $PosFineLiberi - $PosInizioLiberi));
        $risultato["liberi"] = $PostiLiberi;
        
        $PosInizioOccupati = strpos($sorgente, '"', $PosFineLiberi + 1) + 1;
        $PosFineOccupati = strpos($sorgente, '"', $PosInizioOccupati);
        $PostiOccupati = intval(substr($sorgente, $PosInizioOccupati, $PosFineOccupati - $PosInizioOccupati));
        $risultato["occupati"] = $PostiOccupati;
        
        $PostiTotali = $PostiOccupati + $PostiLiberi;
        $risultato["saldo"] = $PostiTotali;
        
        $PosInizioCapienzaDichiarata = strpos($sorgente, 'var sottoTitolo = "Posti Totali: ') + 33;
        $PosFineCapienzaDichiarata = strpos($sorgente, '"', $PosInizioCapienzaDichiarata);
        $CapienzaDichiarata = intval(substr($sorgente, $PosInizioCapienzaDichiarata, $PosFineCapienzaDichiarata - $PosInizioCapienzaDichiarata));
        $risultato["capienza"] = $CapienzaDichiarata;

        return $risultato;
    }
}

function ElementoTrovato($ArrayRisultati)
{
    if($ArrayRisultati === null || $ArrayRisultati === false ||
        count($ArrayRisultati) <= 0 || $ArrayRisultati[0] === null) return false;
        else return true;
}

function SeguiRedirectHTTP($url)
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