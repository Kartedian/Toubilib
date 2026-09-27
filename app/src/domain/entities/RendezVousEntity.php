<?php

namespace toubilib\domain\entities;

use DateTime;

class RendezVousEntity{
    public readonly string $id;

    public function __construct(
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
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
        $this->id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
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
        return new RendezVousEntity($praticien->id, $patient->id, $date, 0, $duree, $dateFin, new \DateTime('now'), $motif);
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