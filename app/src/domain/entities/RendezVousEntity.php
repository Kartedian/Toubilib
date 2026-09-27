<?php

namespace toubilib\domain\entities;

use DateTime;
use toubilib\domain\shared\UuidGenerator;

class RendezVousEntity{
    use UuidGenerator;
    public function __construct(
        public readonly string $id,
        public readonly string $praticien_id,
        public readonly string $patient_id,
        public readonly DateTime $date_heure_debut,
        public int $status,
        public readonly int $duree,
        public readonly DateTime $date_heure_fin,
        public readonly DateTime $date_creation,
        public readonly string $motif_visite
    )
    {
    }

    public function annuler(){
        $og = new DateTime();
        if($this->date_heure_debut < $og)
            throw new \Exception("error");
        
        if($this->status != -1){
            $this->status = -1;
        }
    }

    public static function validation_creationRDV(PraticienEntity $praticien, PatientEntity $patient, DateTime $date, string $motif){
        $praticien->verifierMotif($motif);      

        if(!$praticien->verifierDateRDV($date, $motif)){
            #throw new DateRDVNotValidException();
        }

        $duree = $praticien->calculDureeRDV($motif);
        $dateFin = clone $date;
        $dateFin->modify("+$duree minutes");
        $newRDV = new RendezVousEntity(self::genererUuid() , $praticien->id, $patient->id, $date, 0, $duree, $dateFin, new \DateTime('now'), $motif);

        $praticien->verifierDisponibilite($newRDV);

        return $newRDV;
    }

    public function chevauche(RendezVousEntity $autreRdv): bool{
        if($this->date_heure_debut >= $autreRdv->date_heure_debut && $this->date_heure_debut < $autreRdv->date_heure_fin){
            return true;
        }
        if ($this->date_heure_fin > $autreRdv->date_heure_debut && $this->date_heure_fin <= $autreRdv->date_heure_fin){
            return true;
        }
        return false;
    }
}