<?php
Class Aflegstapel{
    public $Kaarten;

    function __construct(){
        $this->Kaarten = array();
    }

    function PlaatsKaart($kaart){
        array_push($this->Kaarten, $kaart);
    }

    function GeefAlleKaarten(){
        $alleKaarten = array();
        $aantal = count($this->Kaarten);
        foreach($this->Kaarten as $key => $kaart){
            if($key < $aantal - 1){
                array_push($alleKaarten, $kaart);
                unset($this->Kaarten[$key]);
            }
        }
        $this->Kaarten = array_values($this->Kaarten);
        return $alleKaarten;
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