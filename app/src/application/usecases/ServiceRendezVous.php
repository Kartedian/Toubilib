<?php

namespace toubilib\application\usecases;

use DateTimeImmutable;
use Exception;
use Override;
use toubilib\adapters\ToubilibRepositoryManager;
use toubilib\application\dto\RendezVousDTO;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use toubilib\domain\entities\RendezVousEntity;
use toubilib\application\dto\CreateRdvDTO;
use toubilib\application\dto\RendezVousDetailDTO;
use toubilib\application\validators\RdvValidator;
use toubilib\domain\entities\RdvStatus;

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
            $rdv->date_heure_debut->format('d-m-Y H:i:s'), 
            $rdv->date_creation->format('d-m-Y H:i:s'),
            $rdv->status->name, 
            $rdv->duree, 
            $rdv->motif_visite
        );
    }

    public function creer(CreateRdvDTO $dto): RendezVousDetailDTO {
        $validator = new RdvValidator($this->manager->getPraticienRepository(), $this->manager->getPatientRepository());
        $validator->valider($dto);

        $nouveauRdv = $this->reserver($dto);

        return new RendezVousDetailDTO(
            $nouveauRdv->id,
            $nouveauRdv->praticien_id,
            $nouveauRdv->patient_id,
            $nouveauRdv->date_heure_debut->format('d-m-Y H:i:s'), 
            $nouveauRdv->date_heure_fin->format('d-m-Y H:i:s'),
            $nouveauRdv->date_creation->format('d-m-Y H:i:s'),
            $nouveauRdv->status->name, 
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

    public function getById(string $id): RendezVousDetailDTO
    {
        $rdv = $this->manager->getRendezVousRepository()->getById($id);
        
        return new RendezVousDetailDTO(
            $rdv->id,
            $rdv->praticien_id,
            $rdv->patient_id,
            $rdv->date_heure_debut->format('d-m-Y H:i:s'), 
            $rdv->date_heure_fin->format('d-m-Y H:i:s'),
            $rdv->date_creation->format('d-m-Y H:i:s'),
            $rdv->status->name, 
            $rdv->duree, 
            $rdv->motif_visite
        );
    }

    public function setStatusRdv(string $id, RdvStatus $status): RendezVousDetailDTO
    {
        $rdv = $this->manager->getRendezVousRepository()->getById($id);

        switch ($status) {
            case RdvStatus::ANNULER:
                $rdv->annuler();
                break;
            case RdvStatus::HONORER:
                $rdv->honorer();
                break;
            case RdvStatus::IGNORER:
                $rdv->ignorer();
                break;
        }
        $this->manager->getRendezVousRepository()->updateStatus($rdv);

        return new RendezVousDetailDTO(
            $rdv->id,
            $rdv->praticien_id,
            $rdv->patient_id,
            $rdv->date_heure_debut->format('d-m-Y H:i:s'), 
            $rdv->date_heure_fin->format('d-m-Y H:i:s'),
            $rdv->date_creation->format('d-m-Y H:i:s'),
            $rdv->status->name, 
            $rdv->duree, 
            $rdv->motif_visite
        );
    }
}
