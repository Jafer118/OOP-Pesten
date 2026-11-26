<?php
Class Deck{
    public $Kaarten;
    private $waardes;
    private $tekens;

    function __construct(){
        $this->Kaarten = array();
        $this->waardes = array('2','3','4','5','6','7','8','9','10','J','Q','K','A');
        $this->tekens = array('H','S','R','K');
        $this->maakNieuwDeck();
        $this->Schudden();
    }

    private function maakNieuwDeck(){
        $nr = 0;
        for($T=0; $T < count($this->tekens); $T++){
            for($W=0; $W < count($this->waardes); $W++){
                $this->Kaarten[$nr] = new Kaart($this->waardes[$W], $this->tekens[$T]);
                $nr++;
            }
        }
        $this->Kaarten[$nr] = new Kaart('X','J');
        $nr++;
        $this->Kaarten[$nr] = new Kaart('X','J');
    }

    public function Schudden(){
        shuffle($this->Kaarten);
        shuffle($this->Kaarten);
    }

    public function Rapen(){
        $bovensteKaart = array_shift($this->Kaarten);
        return $bovensteKaart;
    }

    public function ShowDeck(){
        echo "<deck onclick='window.location.href=`index.php?Kaart=pakken`;'>";
        foreach ($this->Kaarten as $kaart) {
            echo "<kaart>";
            $kaart->ShowKaart();
            echo "</kaart>";
        }
        echo "</deck>";
    }
}