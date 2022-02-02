const versione = "1.2"
const UrlBaseGraficiPosti = "https://qrbiblio.unipi.it/Home/Chart?IdCat="
var PosizioneUtente
var TimestampUltimoAggiornamento = 0;
var OrdineBiblioteche = []
var DistanzaBiblioteche = []

var TimerAggiornaScritteTempo
var TimerAggiornaPagina

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
							latitudine: "43.71119680445201",
							longitudine: "10.410463494623068",
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
							latitudine: "43.71742660188611",
							longitudine: "10.42718640847391",
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

/***************** MAIN ***************/
{
	document.getElementById("avviso-nojs").style.display = "none"
	document.getElementById("AppVersion").innerText = versione
	for(var i=0; i<Biblioteche.length; i++) OrdineBiblioteche.push(i)

	CaricaImpostazioni()
	RegistraHandler()

	document.getElementById("num-biblio-totali").innerText = Biblioteche.length
	var CapienzaTotale = 0
	Biblioteche.forEach(biblioteca => { CapienzaTotale += biblioteca.capienza } )
	document.getElementById("capienza-totale").innerText = CapienzaTotale

	if (navigator.geolocation) navigator.geolocation.watchPosition(MemorizzaPosizioneUtente, MemorizzaRifiutoPosizioneUtente);
	else MemorizzaRifiutoPosizioneUtente()

	InserisciBoxBiblioteche()
	AggiornaTuttiOrariApertura()
	OrdinaBoxBiblioteche()
	CaricaFrames()
}

function CaricaImpostazioni()
{
	if(localStorage.getItem("auto-refresh") != null) ImpostaAutoRefresh(localStorage.getItem("auto-refresh"))
	if(localStorage.getItem("ordina-distanza") != null) ImpostaOrdinamentoLista(localStorage.getItem("ordina-distanza"))
}

function RegistraHandler()
{
	TimerAggiornaScritteTempo = setInterval(AggiornaPassaggioTempo, 10000)
	document.addEventListener("visibilitychange", function() {
		if(document.hidden) clearInterval(TimerAggiornaScritteTempo)
		else { AggiornaPassaggioTempo(); TimerAggiornaScritteTempo = setInterval(AggiornaPassaggioTempo, 10000); }
	} );
	document.getElementById("ButtAggiorna").addEventListener("click", CaricaFrames)
	document.getElementById("auto-refresh").addEventListener("click", ImpostaAutoRefresh.bind(null, null))
	document.getElementById("ordina-distanza").addEventListener("click", ImpostaOrdinamentoLista.bind(null, null))
}

function ImpostaOrdinamentoLista(NuovoValore)
{
	if(NuovoValore == null) NuovoValore = document.getElementById("ordina-distanza").checked

	document.getElementById("ordina-distanza").checked = NuovoValore
	localStorage.setItem("ordina-distanza", NuovoValore)

	if(NuovoValore = true) OrdinaBoxBiblioteche()
}

function ImpostaAutoRefresh(NuovoValore)
{
	if(NuovoValore == null) NuovoValore = document.getElementById("auto-refresh").checked

	document.getElementById("auto-refresh").checked = NuovoValore
	localStorage.setItem("auto-refresh", NuovoValore)

	if(NuovoValore = true)
	{
		ControllaSeMomentoAutoRefresh();
		TimerAggiornaPagina = setInterval(ControllaSeMomentoAutoRefresh, 30000);
	} else clearInterval(TimerAggiornaPagina)
}

function InserisciBoxBiblioteche()
{
	Biblioteche.forEach(biblioteca => {
		var contenitore = document.createElement('div')
		contenitore.style.display = "inline-block"
		contenitore.classList = "ContenitoreBiblio"
		contenitore.id = "ContenitoreBiblio" + biblioteca.sigla

		var titolo = document.createElement("h2")
		titolo.innerText = biblioteca.NomeBreve
		titolo.title = biblioteca.NomeCompleto
		contenitore.appendChild(titolo)

		var pulsanti = document.createElement("div")
		pulsanti.className = "PulsantiBiblio"

		if(biblioteca.GiorniChiusura != null)
		{
			var ElemOrario = document.createElement("span")
			ElemOrario.id = "Apertura" + biblioteca.sigla
			ElemOrario.title = "Orario di apertura"
			AggiornaOrarioApertura(biblioteca, ElemOrario, contenitore)
			//ElemOrario.innerText = "🕖" + FormattaOrario(biblioteca.OrarioApertura, biblioteca.GiorniChiusura)
			pulsanti.appendChild(ElemOrario)
		}

		if(biblioteca.capienza != null)
		{
			var ElemCapienza = document.createElement("span")
			ElemCapienza.innerText = "🪑" + biblioteca.capienza
			ElemCapienza.title = "Capienza massima"
			pulsanti.appendChild(ElemCapienza)
		}

		if(biblioteca.url != "")
		{
			var ElemSito = document.createElement("a")
			ElemSito.innerText = "🌐web"
			ElemSito.title = "Sito web"
			ElemSito.href = biblioteca.url
			ElemSito.target = "_blank"
			pulsanti.appendChild(ElemSito)
		}

		var AbbiamoCoordinate = (biblioteca.latitudine != "" && biblioteca.longitudine != "")
		if(biblioteca.indirizzo != "" || AbbiamoCoordinate)
		{
			var ElemPosizione = document.createElement("a")
			ElemPosizione.target = "_blank"
			if(AbbiamoCoordinate) ElemPosizione.href = 'https://www.google.com/maps/search/?api=1&query=' + biblioteca.latitudine + ',' + biblioteca.longitudine
			else if(biblioteca.indirizzo != "") ElemPosizione.href = 'https://www.google.com/maps/search/?api=1&query=' + biblioteca.indirizzo + ', Pisa'

			ElemPosizione.innerText = "🗺️"

			var ElemDistanza = document.createElement("span")
			ElemDistanza.id = "Distanza" + biblioteca.sigla
			ElemDistanza.innerText = "posiz."

			ElemPosizione.appendChild(ElemDistanza)
			pulsanti.appendChild(ElemPosizione)
		}
		contenitore.appendChild(pulsanti)

		var Wrapper = document.createElement('div') 
		if(biblioteca.IDGraficoPosti != "")
		{
			Wrapper.className = "WrapperIframe"

			var iframe = document.createElement('iframe');
			iframe.id = "iframe" + biblioteca.sigla
			Wrapper.appendChild(iframe)
		} else
		{
			Wrapper.style.fontSize = "200%"
			Wrapper.style.textAlign = "center"
			Wrapper.style.paddingTop = "33%"
			Wrapper.innerHTML = "Nessun<br/>dato"
		}
		contenitore.appendChild(Wrapper)

		document.getElementById("ListaBiblio").appendChild(contenitore)
	});
}

function OrdinaBoxBiblioteche()
{
	if(PosizioneUtente != null && document.getElementById("ordina-distanza").checked)
	{
		OrdineBiblioteche.sort(function(a, b) { return DistanzaBiblioteche[a] - DistanzaBiblioteche[b] })

		var ElemListaBiblio = document.getElementById("ListaBiblio")
		for(var i=OrdineBiblioteche.length - 1; i >= 0; i--)
		{
			var BibliotecaCheDovrebbeEsserci = Biblioteche[OrdineBiblioteche[i]]
			var IDElemCheDovrebbeEsserci = "ContenitoreBiblio" + BibliotecaCheDovrebbeEsserci.sigla
			var ElemNellaPosizione = ElemListaBiblio.childNodes.item(i)
			if(ElemNellaPosizione.id != IDElemCheDovrebbeEsserci)
			{
				//L'elemento attuale (i-esimo) è fuori posto: bisogna spostarlo
				var ElemCheDovrebbeEsserci = document.getElementById(IDElemCheDovrebbeEsserci)
				if(i < OrdineBiblioteche.length - 1) //l'elemento attuale NON è l'ultimo
				{
					var ElemSuccessivo = document.getElementById("ContenitoreBiblio" + Biblioteche[OrdineBiblioteche[i+1]].sigla)
					ElemListaBiblio.insertBefore(ElemCheDovrebbeEsserci, ElemSuccessivo)
				} else //l'elemento attuale È l'ultimo
				{
					ElemListaBiblio.append(ElemCheDovrebbeEsserci)
				}
			}
		}
	}
}

function AggiornaOrarioApertura(biblioteca, ElemOrario, ElemBiblio)
{
	var StatoApertura = StatoAttualeApertura(biblioteca)
	ElemOrario.style.color = ""
	if(StatoApertura[0] == true)
	{
		ElemBiblio.style.opacity = 1
		ElemOrario.innerText = "🕖Aperta (per " + DaMinutiAOreMinuti(StatoApertura[1]) + ")"
		if(StatoApertura[1] > 75) ElemOrario.style.color = "green";
		else if(StatoApertura[1] > 45) ElemOrario.style.color = "orange";
		else ElemOrario.style.color = "red";
	}
	else
	{
		ElemBiblio.style.opacity = 0.4
		if(StatoApertura[1] == -2) ElemOrario.innerText = "🕖Chiusa permanentemente"
		else if(StatoApertura[1] == -1) ElemOrario.innerText = "🕖Chiusa oggi"
		else if(StatoApertura[1] == 0) ElemOrario.innerText = "🕖Chiusa (turno concluso)" //Turno odierno concluso
		else { ElemOrario.innerText = "🕖Chiusa (aprirà tra " + DaMinutiAOreMinuti(StatoApertura[1]) + ")"
				ElemOrario.style.color = "dodgerblue"; }
	}
	return StatoApertura[0]
}

function DaMinutiAOreMinuti(minuti)
{
	if(minuti < 60) return minuti + " min"
	else return Math.floor(minuti / 60) + "h " + (minuti % 60) + "m"
}

function FormattaOrario(StringheOrario, GiorniChiusura)
{
	//if(GiorniChiusura.count == 7) return "Chiusa permanentemente"
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

function CaricaFrames()
{
	//Se i contenitori delle biblioteche non sono ancora stati generati, esco
	if(document.getElementById("iframe" + Biblioteche[0].sigla) == null) return;

	document.getElementById("loading-total").innerText = Biblioteche.length
	document.getElementById("loading-box").style.display = "block"
	SegnaProgressoCaricamento(0)

	Biblioteche.forEach(biblioteca => {
		if(biblioteca.IDGraficoPosti != "")
		{
			var frame = document.getElementById("iframe" + biblioteca.sigla)
			frame.src = 'about:blank'
			frame.style.opacity = 0
			frame.addEventListener("load", ScrollaIframe.bind(null, "iframe" + biblioteca.sigla))
			frame.src = UrlBaseGraficiPosti + biblioteca.IDGraficoPosti
		} else
		{
			SegnaProgressoCaricamento(parseInt(document.getElementById("loading-done").innerText) + 1)
		}
	})
}

function SegnaProgressoCaricamento(completati)
{
	if(completati >= Biblioteche.length)
	{
		document.getElementById("loading-box").style.display = "none"
		TimestampUltimoAggiornamento = (new Date()).getTime()
		AggiornaPassaggioTempo()
	}
	document.getElementById("loading-done").innerText = completati
	document.querySelector("#loading-box > div > div").style.width = (completati / Biblioteche.length * 100) + "%"
}

function ScrollaIframe(ID)
{
	var frame = document.getElementById(ID)
	frame.style.opacity = 1
	frame.parentElement.scrollTo(0, 165)
	SegnaProgressoCaricamento(parseInt(document.getElementById("loading-done").innerText) + 1)
}

function StatoAttualeApertura(biblio)
{
	var OraAttuale = new Date()
	if(biblio.GiorniChiusura.length == 7) return [false, -2] //Chiusa permanentemente
	if(biblio.GiorniChiusura.includes(OraAttuale.getDay()) ) return [false, -1] //chiusa solo oggi

	var HaGiaAperto = (OraAttuale.getHours() > biblio.OrarioApertura[0]) || (OraAttuale.getHours() == biblio.OrarioApertura[0] && OraAttuale.getMinutes() >= biblio.OrarioApertura[1])
	var HaGiaChiuso = (OraAttuale.getHours() > biblio.OrarioApertura[2]) || (OraAttuale.getHours() == biblio.OrarioApertura[2] && OraAttuale.getMinutes() >= biblio.OrarioApertura[3])

	if(!HaGiaAperto)
	{
		var MinutiPrimaDiApertura = (biblio.OrarioApertura[0] - OraAttuale.getHours()) * 60 + ((biblio.OrarioApertura[1] - OraAttuale.getMinutes()) % 60)
		return [false, MinutiPrimaDiApertura]; //aprirà tra tot minuti
	}
	else if(HaGiaAperto && !HaGiaChiuso)
	{
		var MinutiPrimaDiChiusura = (biblio.OrarioApertura[2] - OraAttuale.getHours()) * 60 + ((biblio.OrarioApertura[3] - OraAttuale.getMinutes()) % 60)
		return [true, MinutiPrimaDiChiusura]; //è aperta
	}
	else if(HaGiaAperto && HaGiaChiuso) return [false, 0]; //apertura odierna conclusa
}

function AggiornaPassaggioTempo()
{
	if(TimestampUltimoAggiornamento != 0)
	{
		var ElemAggiornamento = document.getElementById("DataAggiornamento")
		ElemAggiornamento.innerHTML = TempoPassatoDaTimestamp(TimestampUltimoAggiornamento) + " fa"

		var MinutiTrascorsi = Math.floor( (((new Date()).getTime()) - TimestampUltimoAggiornamento) / 60000)
		if(MinutiTrascorsi < 1) ElemAggiornamento.style.color = "lime"
		else if(MinutiTrascorsi < 3) ElemAggiornamento.style.color = "green"
		else if(MinutiTrascorsi < 5) ElemAggiornamento.style.color = "unset"
		else if(MinutiTrascorsi < 10) ElemAggiornamento.style.color = "blu"
		else if(MinutiTrascorsi < 15) ElemAggiornamento.style.color = "orange"
		else if(MinutiTrascorsi < 30) ElemAggiornamento.style.color = "darkorange"
		else ElemAggiornamento.style.color = "red"

		ElemAggiornamento.title = "Dati aggiornati alle ore " + OreMinutiDaTimestamp(TimestampUltimoAggiornamento)  + " del " + GiornoMeseAnnoDaTimestamp(TimestampUltimoAggiornamento)

		AggiornaTuttiOrariApertura()
	}
}

function AggiornaTuttiOrariApertura()
{
	var NumBiblioAperte = 0
	var CapienzaBiblioAperte = 0
	Biblioteche.forEach(biblio => {
		var aperta = AggiornaOrarioApertura(biblio, document.getElementById("Apertura" + biblio.sigla), document.getElementById("ContenitoreBiblio" + biblio.sigla))
		if(aperta)
		{
			CapienzaBiblioAperte += biblio.capienza;
			NumBiblioAperte++;
		}
	});
	document.getElementById("capienza-aperte").innerText = CapienzaBiblioAperte
	document.getElementById("num-biblio-aperte").innerText = NumBiblioAperte
	document.getElementById("aperture-attuali").style.color = NumBiblioAperte == 0 ? "red" : ""
}

function ControllaSeMomentoAutoRefresh()
{
	if(TimestampUltimoAggiornamento == 0) CaricaFrames()
	else if( ( ((new Date()).getTime()) - TimestampUltimoAggiornamento) >= 300000) CaricaFrames()
}

function TempoPassatoDaTimestamp(timestamp)
{
	var MillisecTrascorsi = ((new Date()).getTime()) - timestamp

	if(MillisecTrascorsi < 60000) return "meno di un minuto"
	else if(MillisecTrascorsi < 3600000)
	{
		var NumMinuti = Math.floor(MillisecTrascorsi / 60000)
		if(NumMinuti == 1) return "un minuto"
		else return NumMinuti + " minuti"
	}
	else if(MillisecTrascorsi < 86400000)
	{
		var NumOre = Math.floor(MillisecTrascorsi / 3600000)
		if(NumOre == 1) return "un'ora"
		else return NumOre + " ore"
	}
	else
	{
		var NumGiorni = Math.floor(MillisecTrascorsi / 86400000)
		if(NumGiorni == 1) return "un giorno"
		else return NumGiorni + " giorni"
	}
}

function OreMinutiDaTimestamp(timestamp)
{
	var Data = new Date(timestamp)

	var RetVal = Data.getHours() + ":"

	if(Data.getMinutes() < 10) RetVal += "0" + Data.getMinutes()
	else RetVal += Data.getMinutes()

	return RetVal
}

function GiornoMeseAnnoDaTimestamp(timestamp)
{
	var Data = new Date(timestamp)
	return Data.getDate() + "/" + (Data.getMonth() + 1) + "/" + Data.getFullYear()
}

/********** GEOLOCALIZZAZIONE **********/

function MemorizzaPosizioneUtente(posizione)
{
	if(posizione.coords.accuracy < 800)
	{
		PosizioneUtente = posizione;
		document.getElementById("ordina-distanza").disabled = false;
		for(var i=0; i < Biblioteche.length; i++)
		{
			var biblioteca = Biblioteche[i]
			var AbbiamoCoordinate = (biblioteca.latitudine != "" && biblioteca.longitudine != "")
			if(AbbiamoCoordinate)
			{
				DistanzaBiblioteche[i] = getDistanceFromLatLonInKm(biblioteca.latitudine, biblioteca.longitudine, PosizioneUtente.coords.latitude, PosizioneUtente.coords.longitude)
				var distanza = DistanzaFormattata([biblioteca.latitudine, biblioteca.longitudine]);
				var elemento = document.getElementById("Distanza" + biblioteca.sigla);
				elemento.innerText = distanza
				elemento.parentElement.title = 'Sei distante ' + distanza + ' dalla biblioteca ' + biblioteca.NomeBreve + '.\nClicca per vedere la mappa';
			} else DistanzaBiblioteche[i] = Number.MAX_SAFE_INTEGER
		}
		OrdinaBoxBiblioteche();
	} else
	{
		PosizioneUtente = null;
		document.getElementById("ordina-distanza").disabled = true;
		Biblioteche.forEach(biblioteca => {
			var elemento = document.getElementById("Posiz" + biblioteca.sigla);
			elemento.innerText = "posiz.";
			elemento.parentElement.title = "Vedi la posizione della biblioteca " + biblioteca.NomeBreve + " sulla mappa";
		});
	}
}

function MemorizzaRifiutoPosizioneUtente(errore)
{
	PosizioneUtente = null;
	document.getElementById("ordina-distanza").disabled = true;

	Biblioteche.forEach(biblioteca => {
		var elemento = document.getElementById("Posiz" + biblioteca.sigla);
		elemento.innerText = "posiz.";
		elemento.title = "Vedi la posizione della biblioteca " + biblioteca.NomeBreve + " sulla mappa";
	});
}

function DistanzaFormattata(posizione)
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

function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2)
{
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

function deg2rad(deg)
{
	return deg * (Math.PI/180)
}