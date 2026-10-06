<?php

namespace toubilib\domain\entities;

use DateTimeImmutable;
use toubilib\domain\shared\UuidGenerator;

class RendezVousEntity{
    use UuidGenerator;
    public function __construct(
        public readonly string $id,
        public readonly string $praticien_id,
        public readonly string $patient_id,
        public readonly DateTimeImmutable $date_heure_debut,
        public int $status,
        public readonly int $duree,
        public readonly DateTimeImmutable $date_heure_fin,
        public readonly DateTimeImmutable $date_creation,
        public readonly string $motif_visite
    )
    {
    }

    public function annuler(){
        $og = new DateTimeImmutable();
        if($this->date_heure_debut < $og)
            throw new \Exception("error");
        
        if($this->status != -1){
            $this->status = -1;
        }
    }

    public static function validation_creationRDV(PraticienEntity $praticien, PatientEntity $patient, DateTimeImmutable $date, string $motif){
        $praticien->verifierMotif($motif);      

        if(!$praticien->verifierDateRDV($date, $motif)){
            #throw new DateRDVNotValidException();
        }

        $duree = $praticien->calculDureeRDV($motif);
        $dateFin = clone $date;
        $dateFin = $dateFin->modify("+$duree minutes");

        if(!$praticien->verifierDisponibilite($date, $dateFin)){
            #throw new DateRDVNotAvailableException();
        }

        $newRDV = new RendezVousEntity(self::genererUuid() , $praticien->id, $patient->id, $date, 0, $duree, $dateFin, new \DateTimeImmutable('now'), $motif);

        return $newRDV;
    }

    public function chevauche(DateTimeImmutable $date_heure_debut, DateTimeImmutable $date_heure_fin): bool{
        if($this->date_heure_debut >= $date_heure_debut && $this->date_heure_debut < $date_heure_fin){
            return true;
        }
        if ($this->date_heure_fin > $date_heure_debut && $this->date_heure_fin <= $date_heure_fin){
            return true;
        }
        return false;
    }
}