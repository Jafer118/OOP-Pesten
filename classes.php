<?php
/*
 * classes.php - alle klassen van het kaartspel Pesten
 * Kaart, Deck, Hand, Aflegstapel en Spelleider
 */

/* ------------------------------------------------------------------ */
/* Hoofdstuk 1: De klasse Kaart                                        */
/* ------------------------------------------------------------------ */
Class Kaart {
  private $waarde;
  private $teken;

  // Initialiseer waarde en teken van de kaart
  function __construct($waarde,$teken){
    $this->waarde = $waarde;
    $this->teken = $teken;
  }

  public function GetWaarde(){return $this->waarde;}
  public function GetTeken(){return $this->teken;}

  // Toon de kaart: voorkant (plaatje van de kaart) of achterkant
  public function ShowKaart($Zichtbaar = false){
    if($Zichtbaar){
      // De joker (X) heeft als plaatje XX.svg, alle andere kaarten teken+waarde (bv. H2.svg)
      if($this->waarde == 'X'){
        echo "<img src='img/XX.svg' />";
      }else{
        echo "<img src='img/" . $this->teken . $this->waarde . ".svg' />";
      }
    }else{
      // Achterkant van de kaart
      echo "<img src='img/rood.svg' />";
    }
  }
}

/* ------------------------------------------------------------------ */
/* Hoofdstuk 1 en 2: Het Deck                                          */
/* ------------------------------------------------------------------ */
Class Deck{
  public $Kaarten;
  private $waardes; // ('2' t/m '10', 'J', 'Q', 'K', 'A')
  private $tekens;  // ('H', 'S', 'R', 'K')

  function __construct(){
    // deze code word uitgevoerd wanneer het object word aangemaakt.
    $this->Kaarten = array();
    $this->waardes = array('2','3','4','5','6','7','8','9','10','J','Q','K','A');
    $this->tekens = array('H', 'S', 'R', 'K');
    $this->maakNieuwDeck();
    $this->Schudden();
  }

  // Maak 52 kaarten (4 tekens x 13 waardes) + 2 jokers (bonus)
  private function maakNieuwDeck(){
    $nr = 0;
    for($T=0; $T < count($this->tekens); $T++){
      for($W=0; $W < count($this->waardes); $W++){
        $this->Kaarten[$nr] = new Kaart($this->waardes[$W], $this->tekens[$T]);
        $nr++;
      }
    }
    // Bonus: 2 jokers toevoegen
    $this->Kaarten[$nr] = new Kaart('X','J');
    $nr++;
    $this->Kaarten[$nr] = new Kaart('X','J');
    $nr++;
  }

  // Toon het deck (klik op het deck = kaart pakken)
  public function ShowDeck(){
    echo "<deck onclick='window.location.href=`index.php?Kaart=pakken`;'>";
    foreach ($this->Kaarten as $kaart) {
      echo "<kaart>";
      $kaart->ShowKaart();
      echo "</kaart>";
    }
    echo "</deck>";
  }

  // Pak de bovenste kaart van het deck
  public function Rapen(){
    $totaal = count($this->Kaarten);              // tel het aantal kaarten in het deck.
    $bovensteKaart = $this->Kaarten[$totaal-1];   // selecteer de bovenste kaart uit het deck.
    unset($this->Kaarten[$totaal-1]);             // Haal de geselecteerde kaart uit het deck.
    return $bovensteKaart;                        // geef de geselecteerde kaart terug.
  }

  // Schud het deck. shuffle werkt niet perfect, dus we doen het 2 keer.
  public function Schudden(){
    shuffle($this->Kaarten);
    shuffle($this->Kaarten);
  }
}

/* ------------------------------------------------------------------ */
/* Hoofdstuk 2 en 4: De Hand van een speler                            */
/* ------------------------------------------------------------------ */
Class Hand{
  public $Kaarten;
  private $spelerNr;

  function __construct($spelerNr){
    $this->spelerNr = $spelerNr;
    $this->Kaarten = array();
  }

  public function ToevoegenAanHand($kaart){
    array_push($this->Kaarten,$kaart); // Voeg kaart toe aan kaarten van hand
  }

  // $id = nummer van de speler die aan de beurt is
  public function ShowHand($id){
    echo "<hand class='P" . $this->spelerNr . "'>";
    foreach ($this->Kaarten as $key => $kaart) {
      // Alleen de kaarten van de speler die aan de beurt is kunnen aangeklikt worden
      if($this->spelerNr==$id){
        echo "<kaart onclick='window.location.href=`index.php?Kaart=".$key."`;'>";
        $kaart->ShowKaart(true);
      }else{
        echo "<kaart>";
        $kaart->ShowKaart();
      }
      echo "</kaart>";
    }
    echo "</hand>";
  }

  public function VerwijderVanHand($id){
    $kaart = $this->Kaarten[$id];  // select kaart via $id
    unset($this->Kaarten[$id]);    // verwijder de geselecteerde kaart van de hand
    $this->herschikHand();
    return $kaart;
  }

  // Zorgt ervoor dat de index van de kaarten in de hand aaneengesloten blijft
  private function herschikHand(){
    $nr=0;
    $tijdelijkDeck = $this->Kaarten;
    $this->Kaarten = array();       // Maak de variable Kaarten leeg
    foreach($tijdelijkDeck as $kaart){
      $this->Kaarten[$nr] = $kaart; // Vul de array Kaarten weer per kaart
      $nr++;
    }
  }
}

/* ------------------------------------------------------------------ */
/* Hoofdstuk 3: De Aflegstapel                                         */
/* ------------------------------------------------------------------ */
Class Aflegstapel{
  public $Kaarten;

  function __construct(){
    $this->Kaarten = array(); // initialiseren van fields.
  }

  function PlaatsKaart($kaart){
    array_push($this->Kaarten,$kaart); // leg de kaart boven op de stapel
  }

  // Geef alle kaarten behalve de bovenste terug (om het deck mee aan te vullen)
  function GeefAlleKaarten(){
    $alleKaarten = array();               // maak een lege array aan.
    $totaal = count($this->Kaarten);
    // zet alle kaarten (behalve de bovenste kaart) in $alleKaarten.
    foreach($this->Kaarten as $nr => $kaart){
      if($nr < $totaal-1){
        $alleKaarten[] = $kaart;
        unset($this->Kaarten[$nr]);       // unset de gekozen kaarten uit de public $Kaarten.
      }
    }
    // De overgebleven kaart opnieuw indexeren (index 0), anders klopt count()-1 niet meer
    $this->Kaarten = array_values($this->Kaarten);
    return $alleKaarten;                  // geef $alleKaarten door.
  }

  function ShowAflegstapel(){
    echo "<aflegstapel>";
    foreach($this->Kaarten as $kaart){
      echo "<kaart>";
      $kaart->ShowKaart(true);
      echo "</kaart>";
    }
    echo "</aflegstapel>";
  }
}

/* ------------------------------------------------------------------ */
/* Hoofdstuk 3, 4 en 5: De Spelleider                                  */
/* ------------------------------------------------------------------ */
Class Spelleider{
  public $Deck;
  public $Aflegstapel;
  public $Spelers;
  private $beurt;
  private $LR;        // speelrichting: true = met de klok mee, false = tegen de klok in
  private $winnaar;   // nummer van de winnaar (null zolang er geen winnaar is)

  function __construct($SpelersAantal){
    //initialiseren van de fields
    $this->LR = true;
    $this->winnaar = null;
    // aanmaken van het deck
    $this->Deck = new Deck();
    // aanmaken van de aflegstapel
    $this->Aflegstapel = new Aflegstapel();
    // aanmaken van de spelers
    $this->Spelers = array();
    for($i=0; $i < $SpelersAantal; $i++){
      $this->Spelers[$i] = new Hand($i);
    }
    // kaarten verdelen (iedere speler 7 kaarten)
    for($k=0; $k < 7; $k++){
      for($i=0; $i < $SpelersAantal; $i++){
        $this->Spelers[$i]->ToevoegenAanHand($this->Deck->Rapen());
      }
    }
    // Kaart van het deck rapen en op de aflegstapel plaatsen
    $this->Aflegstapel->PlaatsKaart($this->Deck->Rapen());
    // Random beginnende speler kiezen.
    $this->beurt = rand(0, $SpelersAantal-1);
  }

  function Show(){
    if($this->winnaar !== null){
      echo "<p>Speler ".($this->winnaar+1)." heeft gewonnen!</p>";
    }else{
      echo "<p>Speler ".($this->beurt+1)." is aan de beurt</p>";
    }
    for ($i=0; $i < count($this->Spelers); $i++) {
      $this->Spelers[$i]->ShowHand($this->beurt);
    }
    echo "<tafel>";
    $this->Deck->ShowDeck();
    $this->Aflegstapel->ShowAflegstapel();
    echo "</tafel>";
  }

  // Controleer of de kaart mag worden gespeeld en speel hem dan
  private function speelKaart($kaartid){
    $hand = $this->Spelers[$this->beurt];
    if(!isset($hand->Kaarten[$kaartid])){ return; }

    $speelkaart = $hand->Kaarten[$kaartid];
    $aantalOpStapel = count($this->Aflegstapel->Kaarten);
    $bovenste = $this->Aflegstapel->Kaarten[$aantalOpStapel-1];

    if(empty($this->Aflegstapel->Kaarten)
       || $speelkaart->GetWaarde()==$bovenste->GetWaarde()
       || $speelkaart->GetTeken()==$bovenste->GetTeken()
       || $speelkaart->GetWaarde()=='J'
       || $speelkaart->GetWaarde()=='X'      // joker mag op iedere kaart
       || $bovenste->GetWaarde()=='X'){      // na een joker mag elke kaart opgegooid worden

      // Spel winnen: heeft de speler nog 1 kaart en is dat een pestkaart, dan 2 kaarten rapen
      $this->winnen();

      $kaart = $this->Spelers[$this->beurt]->VerwijderVanHand($kaartid);

      // Heeft de speler geen kaarten meer, dan is hij de winnaar
      if(count($this->Spelers[$this->beurt]->Kaarten) == 0){
        $this->winnaar = $this->beurt;
      }

      switch ($kaart->GetWaarde()) {
        case '2':
          $this->volgendeSpeler();
          $this->kaartPakken();
          $this->kaartPakken();
        break;
        case '8':
          $this->volgendeSpeler();
          $this->volgendeSpeler();
        break;
        case '10':
          // iedere speler geeft een willekeurige kaart door aan de volgende speler
          $aantal = count($this->Spelers);
          $doorgegeven = array();
          // eerst kiest iedere speler een willekeurige kaart uit zijn hand
          for($i=0; $i < $aantal; $i++){
            $kaarten = count($this->Spelers[$this->beurt]->Kaarten);
            if($kaarten > 0){
              $willekeurig = rand(0, $kaarten-1);
              $doorgegeven[$i] = $this->Spelers[$this->beurt]->VerwijderVanHand($willekeurig);
            }else{
              $doorgegeven[$i] = null;
            }
            $this->volgendeSpeler();
          }
          // daarna gaat iedere kaart naar de volgende speler
          for($i=0; $i < $aantal; $i++){
            $this->volgendeSpeler();
            if($doorgegeven[$i] !== null){
              $this->Spelers[$this->beurt]->ToevoegenAanHand($doorgegeven[$i]);
            }
          }
          // de beurt van de speler die de 10 speelde is voorbij
          $this->volgendeSpeler();
        break;
        case 'K':
          //niks, dezelfde speler mag nog een keer
        break;
        case 'A':
          // draai de richting (LR)
          $this->LR = !$this->LR;
          $this->volgendeSpeler();
        break;
        case 'X':
          // joker: de volgende speler moet 5 kaarten rapen
          $this->volgendeSpeler();
          for($i=0; $i < 5; $i++){
            $this->kaartPakken();
          }
        break;
        default: //Dit zijn geen pestkaarten
          $this->volgendeSpeler();
        break;
      }
      $this->Aflegstapel->PlaatsKaart($kaart);
    }
  }

  // Klik op een kaart (nummer in de hand) of op het deck ("pakken")
  public function Klik($waarde){
    if($this->winnaar !== null){ return; } // het spel is afgelopen
    if($waarde=="pakken"){
      $this->kaartPakken();
      $this->volgendeSpeler();
    }else{
      $this->speelKaart($waarde);
    }
  }

  private function volgendeSpeler(){
    if($this->LR){$this->beurt++;}else{$this->beurt--;}
    if($this->beurt==count($this->Spelers)){$this->beurt=0;}
    if($this->beurt==-1){$this->beurt=count($this->Spelers)-1;}
  }

  // Pak een kaart van de stapel en voeg de kaart toe aan de speler die aan de beurt is
  private function kaartPakken(){
    $this->deckAanvullen();
    if(count($this->Deck->Kaarten) > 0){
      $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
    }
    $this->deckAanvullen();
  }

  // Heeft het deck minder dan 3 kaarten? Haal alle kaarten (op 1 na) uit de aflegstapel,
  // voeg ze toe aan het deck en schud het deck.
  private function deckAanvullen(){
    if(count($this->Deck->Kaarten)<3){
      $kaarten = $this->Aflegstapel->GeefAlleKaarten();
      foreach ($kaarten as $kaart) {
        array_push($this->Deck->Kaarten,$kaart);
      }
      $this->Deck->Schudden();
    }
  }

  // Een spel mag nooit beeindigd worden met een pestkaart:
  // heeft de speler nog 1 kaart en is dat een pestkaart, dan moet hij 2 kaarten pakken.
  private function winnen(){
    $hand = $this->Spelers[$this->beurt];
    if(count($hand->Kaarten) == 1){
      $pestkaarten = array('2','8','10','J','K','A','X');
      if(in_array($hand->Kaarten[0]->GetWaarde(), $pestkaarten)){
        $this->kaartPakken();
        $this->kaartPakken();
      }
    }
  }
}
