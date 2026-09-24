<?php

namespace toubilib\domain\entities;

use DateTime;

class RendezVousEntity{
    public function __construct(
        public readonly string $id,
        public readonly DateTime $date_heure_debut,
        public readonly DateTime $date_heure_fin,
        public readonly DateTime $date_creation,
        public int $statut,
        public readonly int $duree,
        public readonly string $motif_visite
    )
    {}

    public function annuler(){
        $og = new DateTime();
        if($this->date_heure_debut < $og)
            throw new \Exception("error");
        
        if($this->statut != -1){
            $this->statut = -1;
        }
    }
}