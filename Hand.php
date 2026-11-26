<?php
Class Hand {
    public $Kaarten;
    private $spelerNr;

    function __construct($spelerNr){
        $this->Kaarten = array();
        $this->spelerNr = $spelerNr;
    }

    public function ToevoegenAanHand($kaart){
        array_push($this->Kaarten,$kaart);
    }

    public function VerwijderVanHand($id){
        $kaart = $this->Kaarten[$id];
        unset($this->Kaarten[$id]);
        $this->herschikHand();
        return $kaart;
    }

    private function herschikHand(){
        $nr = 0;
        $tijdelijkDeck = $this->Kaarten;
        $this->Kaarten = array();
        foreach($tijdelijkDeck as $kaart){
            $this->Kaarten[$nr] = $kaart;
            $nr++;
        }
    }

    public function ShowHand($id){
        echo "<hand class='P" . $this->spelerNr . "'>";
        foreach ($this->Kaarten as $key => $kaart) {
            echo "<kaart onclick='window.location.href=`index.php?Kaart=".$key."`;'>";
            if($this->spelerNr==$id){$kaart->ShowKaart(true);}else{$kaart->ShowKaart();}
            echo "</kaart>";
        }
        echo "</hand>";
    }
}