<?php
Class Spelleider{
    public $Deck;
    public $Aflegstapel;
    public $Spelers;
    private $beurt;
    private $LR;

    function __construct($SpelersAantal){
        $this->Deck = new Deck();
        $this->Aflegstapel = new Aflegstapel();
        $this->Spelers = array();
        $this->LR = true;

        for($s = 0; $s < $SpelersAantal; $s++){
            $this->Spelers[$s] = new Hand($s);
        }

        for($i = 0; $i < 7; $i++){
            for($s = 0; $s < $SpelersAantal; $s++){
                $this->Spelers[$s]->ToevoegenAanHand($this->Deck->Rapen());
            }
        }

        $this->Aflegstapel->PlaatsKaart($this->Deck->Rapen());
        $this->beurt = rand(0, $SpelersAantal - 1);
    }

    function VolgendeSpeler(){
        if($this->LR){
            $this->beurt++;
        }else{
            $this->beurt--;
        }
        if($this->beurt == count($this->Spelers)){
            $this->beurt = 0;
        }
        if($this->beurt == -1){
            $this->beurt = count($this->Spelers) - 1;
        }
    }

    private function winnen(){
        $speler = $this->Spelers[$this->beurt];
        if(count($speler->Kaarten) == 1){
            $laatsteKaart = $speler->Kaarten[0];
            $pestkaarten = array('2','8','10','J','K','A','X');
            if(in_array($laatsteKaart->GetWaarde(), $pestkaarten)){
                $speler->ToevoegenAanHand($this->Deck->Rapen());
                $speler->ToevoegenAanHand($this->Deck->Rapen());
            }
        }
    }

    private function speelKaart($kaartid){
        $this->winnen();
        $bovenste = $this->Aflegstapel->Kaarten[count($this->Aflegstapel->Kaarten)-1];
        $gespeeldeKaart = $this->Spelers[$this->beurt]->Kaarten[$kaartid];

        if(
            empty($this->Aflegstapel->Kaarten) ||
            $gespeeldeKaart->GetWaarde() == $bovenste->GetWaarde() ||
            $gespeeldeKaart->GetTeken() == $bovenste->GetTeken() ||
            $gespeeldeKaart->GetWaarde() == 'J' ||
            $gespeeldeKaart->GetWaarde() == 'X'
        ){
            $kaart = $this->Spelers[$this->beurt]->VerwijderVanHand($kaartid);
            $this->Aflegstapel->PlaatsKaart($kaart);

            switch ($kaart->GetWaarde()) {
                case '2':
                    $this->VolgendeSpeler();
                    $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
                    $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
                    $this->VolgendeSpeler();
                    break;
                case '8':
                    $this->VolgendeSpeler();
                    $this->VolgendeSpeler();
                    break;
                case '10':
                    for($s = 0; $s < count($this->Spelers); $s++){
                        if(count($this->Spelers[$s]->Kaarten) > 0){
                            $randIndex = rand(0, count($this->Spelers[$s]->Kaarten) - 1);
                            $doorgeefKaart = $this->Spelers[$s]->VerwijderVanHand($randIndex);
                            $this->Spelers[$s]->doorgeefKaart = $doorgeefKaart;
                        }
                    }
                    for($s = 0; $s < count($this->Spelers); $s++){
                        if(isset($this->Spelers[$s]->doorgeefKaart)){
                            $volgende = ($s + 1) % count($this->Spelers);
                            $this->Spelers[$volgende]->ToevoegenAanHand($this->Spelers[$s]->doorgeefKaart);
                            unset($this->Spelers[$s]->doorgeefKaart);
                        }
                    }
                    $this->VolgendeSpeler();
                    break;
                case 'K':
                    break;
                case 'A':
                    $this->LR = !$this->LR;
                    $this->VolgendeSpeler();
                    break;
                case 'X':
                    $this->VolgendeSpeler();
                    for($p = 0; $p < 5; $p++){
                        $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
                    }
                    $this->VolgendeSpeler();
                    break;
                default:
                    $this->VolgendeSpeler();
                    break;
            }
        }
    }

    public function Klik($waarde){
        if($waarde=="pakken"){
            $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
            if(count($this->Deck->Kaarten)<3){
                $kaarten = $this->Aflegstapel->GeefAlleKaarten();
                foreach ($kaarten as $kaart) {
                    array_push($this->Deck->Kaarten,$kaart);
                }
                $this->Deck->Schudden();
            }
            $this->VolgendeSpeler();
        }else{
            $this->speelKaart($waarde);
        }
    }

    function Show(){
        for ($i=0; $i < count($this->Spelers); $i++) {
            $this->Spelers[$i]->ShowHand($this->beurt);
        }
        echo "<tafel>";
        $this->Deck->ShowDeck();
        $this->Aflegstapel->ShowAflegstapel();
        echo "</tafel>";
    }
}