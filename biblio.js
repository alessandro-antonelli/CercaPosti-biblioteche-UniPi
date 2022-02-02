const versione = "1.1"
var PosizioneUtente
const UrlBaseGraficiPosti = "https://qrbiblio.unipi.it/Home/Chart?IdCat="

const Biblioteche = [
						{	sigla: "AGR",
							NomeBreve: "Agraria",
							NomeCompleto: "Agraria",
							IDGraficoPosti: "10a1ea41-35bc-4ec6-81af-e39cabefa260",
							capienza: 11,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6, 0],
							indirizzo: "Via del Borghetto 80",
							latitudine: "43.7119425",
							longitudine: "10.411518",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-1/agraria"
						},
						{	sigla: "ECO",
							NomeBreve: "Economia",
							NomeCompleto: "Economia",
							IDGraficoPosti: "",
							capienza: 0,
							OrarioApertura: null,
							GiorniChiusura: [0,1,2,3,4,5,6],
							indirizzo: "Via Cosimo Ridolfi 10",
							latitudine: "43.70694",
							longitudine: "10.41297",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-1/economia"
						},
						{	sigla: "VET",
							NomeBreve: "Veterinaria",
							NomeCompleto: "Medicina veterinaria",
							IDGraficoPosti: "8a1e70ec-7997-4097-873c-084dd8414a38",
							capienza: 18,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6,0],
							indirizzo: "Viale delle Piagge 2",
							latitudine: "43.70694",
							longitudine: "10.41297",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-1/medicina-veterinaria"
						},
						{	sigla: "GIU",
							NomeBreve: "Giurisprudenza",
							NomeCompleto: "Giurisprudenza e Scienze politiche",
							IDGraficoPosti: "0d63f72e-4404-4f4d-bead-5bfbdee7b5b4",
							capienza: 70,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6, 0],
							indirizzo: "Palazzo La Sapienza, Via Curtatone e Montanara 15",
							latitudine: "43.7173353",
							longitudine: "10.3973234",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-2/giurisprudenza"
						},
						{	sigla: "CHI",
							NomeBreve: "Chimica",
							NomeCompleto: "Chimica",
							IDGraficoPosti: "e4c5db0e-aec8-422d-a2d8-e2b01bfc1da7",
							capienza: 28,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6, 0],
							indirizzo: "Via Moruzzi 13",
							latitudine: "43.7188423",
							longitudine: "10.4230224",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-3/chimica"
						},
						{	sigla: "MIF",
							NomeBreve: "Matematica",
							NomeCompleto: "Matematica, Informatica, Fisica",
							IDGraficoPosti: "c7b7fb0e-3175-4e45-a301-bcf8e2dfcc93",
							capienza: 101,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6, 0],
							indirizzo: "Polo Fibonacci, Largo Bruno Pontecorvo 3",
							latitudine: "43.72098398202726",
							longitudine: "10.407881847439574",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-3/matematica-informatica-fisica"
						},
						{	sigla: "SNA",
							NomeBreve: "Biologia",
							NomeCompleto: "Scienze naturali e ambientali",
							IDGraficoPosti: "39b31a93-86b4-4928-a94f-890b11c317a7",
							capienza: 24,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6,0],
							indirizzo: "Via Derna 1",
							latitudine: "43.7185734",
							longitudine: "10.3952457",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-3/scienze-naturali-e-ambientali"
						},
						{	sigla: "MED",
							NomeBreve: "Medicina",
							NomeCompleto: "Medicina e chirurgia, Farmacia",
							IDGraficoPosti: "d334ad11-584b-4b87-be13-322463367eaa",
							capienza: 45,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6,0],
							indirizzo: "Ospedale Santa Chiara edificio 7, Via Savi 10",
							latitudine: "43.7199274",
							longitudine: "10.3935864",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-4/medicina-e-chirurgia-farmacia"
						},
						{	sigla: "ING",
							NomeBreve: "Ingegneria",
							NomeCompleto: "Ingegneria",
							IDGraficoPosti: "a96d84ba-46e8-47a1-b947-ab98a8746d6f",
							capienza: 59,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6,0],
							indirizzo: "Largo Lucio Lazzarino 1",
							latitudine: "43.721135",
							longitudine: "10.389622",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-5/ingegneria"
						},
						{	sigla: "ANG",
							NomeBreve: "Anglistica",
							NomeCompleto: "Anglistica",
							IDGraficoPosti: "697b9891-2510-4813-a12d-b8e0b34923d8",
							capienza: 30,
							OrarioApertura: [9, 0, 18, 0],
							GiorniChiusura: [6,0],
							indirizzo: "Via Santa Maria 67",
							latitudine: "43.7189526",
							longitudine: "10.397341",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-6/anglistica"
						},
						{	sigla: "ANT",
							NomeBreve: "Antichistica",
							NomeCompleto: "Antichistica, linguistica, germanistica, slavistica",
							IDGraficoPosti: "4c346722-ad75-4c46-a4f1-6184abfa3b44",
							capienza: 94,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6, 0],
							indirizzo: "Via Santa Maria 44",
							latitudine: "43.719499131535336",
							longitudine: "10.397896646166586",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-6/antichistica-linguistica-germanistica-slavistica"
						},
						{	sigla: "FIL",
							NomeBreve: "Filosofia",
							NomeCompleto: "Filosofia e storia",
							IDGraficoPosti: "899b1b6f-80f3-4969-b0e2-aedbde979a09",
							capienza: 49,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6,0],
							indirizzo: "Palazzo Carità, Via Pasquale Paoli 9",
							latitudine: "43.7183695",
							longitudine: "10.3988073",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-6/filosofia-e-storia"
						},
						{	sigla: "ITA",
							NomeBreve: "Italianistica",
							NomeCompleto: "Italianistica e romanistica",
							IDGraficoPosti: "49594563-6361-4304-8992-1e75d97da7f7",
							capienza: 52,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6,0],
							indirizzo: "Palazzo Matteucci, Piazza Torricelli 2",
							latitudine: "43.717907",
							longitudine: "10.3981708",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-6/italianistica-romanistica"
						},
						{	sigla: "ART",
							NomeBreve: "Arte",
							NomeCompleto: "Storia delle arti",
							IDGraficoPosti: "ce5e3891-70cd-4998-add2-4441f46d83c8",
							capienza: 35,
							OrarioApertura: [9, 0, 20, 0],
							GiorniChiusura: [6,0],
							indirizzo: "Via Nicola Pisano, 40/A",
							latitudine: "43.7172601",
							longitudine: "10.3926523",
							url: "http://www.sba.unipi.it/it/biblioteche/polo-6/storia-delle-arti"
						}
					]

function SegnaProgressoCaricamento(completati)
{
	//alert("SegnaProgressoCaricamento: " + completati + "/" + Biblioteche.length)
	if(completati >= Biblioteche.length) document.getElementById("loading-box").style.display = "none"
	document.getElementById("loading-done").innerText = completati
	document.querySelector("#loading-box > div > div").style.width = (completati / Biblioteche.length * 100) + "%"
}

{
	document.getElementById("AppVersion").innerText = versione
	//document.getElementById("ButtAggiorna").addEventListener("click", AggiornaFrames)

	var CapienzaTotale = 0
	Biblioteche.forEach(biblioteca => { CapienzaTotale += biblioteca.capienza } )
	document.getElementById("capienza-totale").innerText = CapienzaTotale

	if (navigator.geolocation) navigator.geolocation.watchPosition(MemorizzaPosizioneUtente, MemorizzaRifiutoPosizioneUtente);
	else MemorizzaRifiutoPosizioneUtente()





	document.getElementById("loading-total").innerText = Biblioteche.length
	document.getElementById("loading-box").style.display = "block"
	SegnaProgressoCaricamento(0)

	Biblioteche.forEach(biblioteca => {
		var contenitore = document.createElement('div')
		contenitore.style.display = "inline-block"
		contenitore.classList = "ContenitoreBiblio"

		var titolo = document.createElement("h2")
		titolo.innerText = biblioteca.NomeBreve
		titolo.title = biblioteca.NomeCompleto
		contenitore.appendChild(titolo)

		var pulsanti = document.createElement("div")
		pulsanti.className = "PulsantiBiblio"

		if(biblioteca.GiorniChiusura != null) pulsanti.innerHTML += ` <span title="Orario di apertura">🕖${FormattaOrario(biblioteca.OrarioApertura, biblioteca.GiorniChiusura)}</span>`

		if(biblioteca.capienza != null) pulsanti.innerHTML += ` <span title="Capienza massima">🪑${biblioteca.capienza}</span>`

		if(biblioteca.url != "") pulsanti.innerHTML += ` <a title="Sito web" target="_blank" href="${biblioteca.url}">🌐web</a>`

		var AbbiamoCoordinate = (biblioteca.latitudine != "" && biblioteca.longitudine != "")
		if(biblioteca.indirizzo != "" || AbbiamoCoordinate)
		{
			if(AbbiamoCoordinate)
			{
				const URLmappa = 'https://www.google.com/maps/search/?api=1&query=' + biblioteca.latitudine + ',' + biblioteca.longitudine;
				pulsanti.innerHTML += ` <div><a id="Posiz${biblioteca.sigla}" title="Posizione" target="_blank" href="${URLmappa}">🗺️ ${biblioteca.indirizzo}</a></div>`
			} else pulsanti.innerHTML += ` <div id="Posiz${biblioteca.sigla}" title="Posizione">🗺️ ${biblioteca.indirizzo}</div>`
		}
		contenitore.appendChild(pulsanti)

		var Wrapper = document.createElement('div') 
		if(biblioteca.IDGraficoPosti != "")
		{
			Wrapper.className = "WrapperIframe"

			var iframe = document.createElement('iframe');
			iframe.id = "iframe" + biblioteca.sigla
			iframe.src = UrlBaseGraficiPosti + biblioteca.IDGraficoPosti
			iframe.addEventListener("load", ScrollaIframe.bind(null, "iframe" + biblioteca.sigla))
			iframe.style.opacity = 0
			Wrapper.appendChild(iframe)
		} else
		{
			Wrapper.style.fontSize = "200%"
			Wrapper.style.textAlign = "center"
			Wrapper.style.paddingTop = "100px"
			Wrapper.innerHTML = "Nessun<br/>dato"
			SegnaProgressoCaricamento(parseInt(document.getElementById("loading-done").innerText) + 1)
		}
		contenitore.appendChild(Wrapper)

		document.getElementById("ListaBiblio").appendChild(contenitore)
	});
}

function FormattaOrario(StringheOrario, GiorniChiusura)
{
	//if(GiorniChiusura.count = 7) return "Chiusa permanentemente"
	if(GiorniChiusura.includes( (new Date()).getDay() ) ) return "Chiusa" //solo oggi

	var RetVal = StringheOrario[0]
	if(StringheOrario[1] != 0)
	{
		if(StringheOrario[1] >= 10) RetVal += ":" + StringheOrario[1]
		else RetVal += ":0" + StringheOrario[1]
	}
	RetVal += " - " + StringheOrario[2]
	if(StringheOrario[3] != 0)
	{
		if(StringheOrario[3] >= 10) RetVal += ":" + StringheOrario[3]
		else RetVal += ":0" + StringheOrario[3]
	}
	return RetVal
}

function ScrollaIframe(ID)
{
	var frame = document.getElementById(ID)
	frame.style.opacity = 1
	frame.parentElement.scrollTo(0, 165)
	SegnaProgressoCaricamento(parseInt(document.getElementById("loading-done").innerText) + 1)
}

/* function AggiornaFrames()
{
	Biblioteche.forEach(biblioteca => {
		var frame = document.getElementById("iframe" + biblioteca.sigla)
		frame.style.opacity = 0
		frame.src += ''
	})
} */

/********** GEOLOCALIZZAZIONE **********/

function MemorizzaPosizioneUtente(posizione)
{
	if(posizione.coords.accuracy <= 2000)
	{
		PosizioneUtente = posizione;
		Biblioteche.forEach(biblioteca => {
			var elemento = document.getElementById("Posiz" + biblioteca.sigla);
			elemento.innerText = DistanzaFormattata([biblioteca.longitudine, biblioteca.latitudine]);
			elemento.title = 'Sei distante ' + distanza + ' dalla biblioteca ' + biblioteca.NomeBreve + '.\nClicca per vedere la mappa';
		});
	} else
	{
		PosizioneUtente = null;
		Biblioteche.forEach(biblioteca => {
			var elemento = document.getElementById("Posiz" + biblioteca.sigla);
			elemento.innerText = "posiz.";
			elemento.title = "Vedi la posizione della biblioteca " + biblioteca.NomeBreve + " sulla mappa";
		});
	}
}

function MemorizzaRifiutoPosizioneUtente(errore)
{
	PosizioneUtente = null;

	Biblioteche.forEach(biblioteca => {
		var elemento = document.getElementById("Posiz" + biblioteca.sigla);
		elemento.innerText = "posiz.";
		elemento.title = "Vedi la posizione della biblioteca " + biblioteca.NomeBreve + " sulla mappa";
	});
}

exports.DistanzaFormattata = function(posizione)
{
	const km = getDistanceFromLatLonInKm(posizione[0], posizione[1], PosizioneUtente.coords.latitude, PosizioneUtente.coords.longitude)
	return FormattaDistanza(km);
}

function FormattaDistanza(distanzaKm)
{
	if(distanzaKm < 1) { var MultipliCinquanta = Math.round(distanzaKm*20); return MultipliCinquanta*50 + 'm'; } //Era: return Math.round(distanzaKm*1000) + 'm';
	else if(distanzaKm < 10 && Math.round((distanzaKm - Math.floor(distanzaKm)) * 10) == 0) return Math.floor(distanzaKm) + 'km';
	else if(distanzaKm < 10 && Math.round((distanzaKm - Math.floor(distanzaKm)) * 10) != 0) return Math.floor(distanzaKm) + ',' + Math.round((distanzaKm - Math.floor(distanzaKm)) * 10) + 'km';
	else return Math.round(distanzaKm) + 'km';
}

exports.getDistanceFromLatLonInKm = getDistanceFromLatLonInKm;
function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
	var R = 6371; // Radius of the earth in km
	var dLat = deg2rad(lat2-lat1); // deg2rad below
	var dLon = deg2rad(lon2-lon1);
	var a =
		Math.sin(dLat/2) * Math.sin(dLat/2) +
		Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
		Math.sin(dLon/2) * Math.sin(dLon/2)
	;
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var d = R * c; // Distance in km
	return d;
}

function deg2rad(deg) {
	return deg * (Math.PI/180)
}