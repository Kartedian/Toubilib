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
        public RdvStatus $status,
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
        
        if($this->status !== RdvStatus::NEUTRE){
            throw new \Exception("error");
        }
        $this->status = RdvStatus::ANNULER;
    }

    public function honorer(){
        $og = new DateTimeImmutable();
        if($this->date_heure_fin > $og)
            throw new \Exception("error");
        
        if($this->status !== RdvStatus::NEUTRE){
            throw new \Exception("error");
        }

        $this->status = RdvStatus::HONORER;
    }

    public function ignorer(){
        $og = new DateTimeImmutable();
        if($this->date_heure_fin > $og)
            throw new \Exception("error");
        
        if($this->status !== RdvStatus::NEUTRE){
            throw new \Exception("error");
        }
        
        $this->status = RdvStatus::IGNORER;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPraticien_id(): string
    {
        return $this->praticien_id;
    }

    public function getPatient_id(): string
    {
        return $this->patient_id;
    }

    public function getDate_heure_debut(): DateTimeImmutable
    {
        return $this->date_heure_debut;
    }

    public function getDate_heure_fin(): DateTimeImmutable
    {
        return $this->date_heure_fin;
    }

    public function getDateCreation(): DateTimeImmutable
    {
        return $this->date_creation;
    }

    public function getStatus(): RdvStatus
    {
        return $this->status;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getMotif(): string
    {
        return $this->motif_visite;
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

        $newRDV = new RendezVousEntity(self::genererUuid() , $praticien->id, $patient->id, $date, RdvStatus::NEUTRE, $duree, $dateFin, new \DateTimeImmutable('now'), $motif);

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