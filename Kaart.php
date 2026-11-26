<?php
Class Kaart {
    private $waarde;
    private $teken;

    function __construct($waarde,$teken){
        $this->waarde = $waarde;
        $this->teken = $teken;
    }

    public function GetWaarde(){return $this->waarde;}
    public function GetTeken(){return $this->teken;}

    public function ShowKaart($Zichtbaar = false){
        if($Zichtbaar){
            $bestand = $this->waarde === 'X'
                ? 'XX'
                : $this->teken . $this->waarde;
            echo "<img src='img/" . $bestand . ".svg' />";
        }else{
            echo "<img src='img/blauw.svg' />";
        }
    }
}