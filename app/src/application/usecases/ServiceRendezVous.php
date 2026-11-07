<?php

namespace toubilib\application\usecases;

use DateTimeImmutable;
use Exception;
use toubilib\adapters\ToubilibRepositoryManager;
use toubilib\application\dto\RendezVousDTO;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use toubilib\domain\entities\RendezVousEntity;
use toubilib\application\dto\CreateRdvDTO;
use toubilib\application\validators\RdvValidator;

class ServiceRendezVous implements ServiceRendezVousInterface{
    private ToubilibRepositoryManager $manager;

    public function __construct()
    {
        $this->manager = ToubilibRepositoryManager::getInstance();
    }

    public function reserver(CreateRdvDTO $dto): RendezVousEntity 
    {
        $praticienRepo = $this->manager->getPraticienRepository();
        $praticien = $praticienRepo->chargerPraticienAvecRdvsDuJour($dto->idPraticien, $dto->dateHeure);

        $patientRepo = $this->manager->getPatientRepository();
        $patient = $patientRepo->getById($dto->idPatient);

        $nouveauRdv = RendezVousEntity::validation_creationRDV(
            $praticien, 
            $patient, 
            $dto->dateHeure, 
            $dto->motif
        );

        $rdvRepo = $this->manager->getRendezVousRepository();
        $rdvRepo->sauvegarder($nouveauRdv);

        return $nouveauRdv;
    }

    public function annuler(string $id): RendezVousDTO{
        try{
            $rdvRepo = $this->manager->getRendezVousRepository();
            $rdv = $rdvRepo->getById($id);
        }
        catch (Exception $e){
            throw new Exception('RDV not found!!');
        }
        
        $rdv->annuler();

        $rdvRepo->updateStatus($rdv);

        return new RendezVousDTO(
            $rdv->id,
            $rdv->praticien_id,
            $rdv->patient_id,
            $rdv->date_heure_debut->format('d-m-Y H:i:s'), 
            $rdv->date_heure_fin->format('d-m-Y H:i:s'),
            $rdv->date_creation->format('d-m-Y H:i:s'),
            $rdv->status, 
            $rdv->duree, 
            $rdv->motif_visite
        );
    }

    public function creer(CreateRdvDTO $dto): RendezVousDTO {
        $validator = new RdvValidator($this->manager->getPraticienRepository(), $this->manager->getPatientRepository());
        $validator->valider($dto);

        $nouveauRdv = $this->reserver($dto);

        return new RendezVousDTO(
            $nouveauRdv->id,
            $nouveauRdv->praticien_id,
            $nouveauRdv->patient_id,
            $nouveauRdv->date_heure_debut->format('d-m-Y H:i:s'), 
            $nouveauRdv->date_heure_fin->format('d-m-Y H:i:s'),
            $nouveauRdv->date_creation->format('d-m-Y H:i:s'),
            $nouveauRdv->status, 
            $nouveauRdv->duree, 
            $nouveauRdv->motif_visite
        );
    }

    public function getRdvsByPraticienId(string $id): array
    {
        return array_map(
            fn($rdv) => RendezVousDTO::fromEntity($rdv),
            $this->manager->getRendezVousRepository()->getByPraticienId($id)
        );
    }

    public function getRdvsByDates_IdPraticienId(string $id, DateTimeImmutable $dateDebut, DateTimeImmutable $datefin): array
    {
        return array_map(
            fn($rdv) => RendezVousDTO::fromEntity($rdv),
            $this->manager->getRendezVousRepository()->getByDates_IdPraticienId($id, $dateDebut, $datefin)
        );
    }
}
