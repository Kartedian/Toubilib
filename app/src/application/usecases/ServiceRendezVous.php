<?php

namespace toubilib\application\usecases;

use Exception;
use toubilib\application\dto\RendezVousDTO;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use toubilib\application\ports\spi\RendezVousRepositoryInterface;
use toubilib\domain\entities\RendezVousEntity;
use toubilib\application\dto\CreateRdvDTO;
use toubilib\application\validators\RdvValidator;

class ServiceRendezVous implements ServiceRendezVousInterface{

    public function __construct(
        private readonly RendezVousRepositoryInterface $repository,
        private readonly RdvValidator $validator
    )
    {}

    public function annuler(string $id): RendezVousDTO{
        try{
            $rdv = $this->repository->getById($id);
        }
        catch (Exception $e){
            throw new Exception('RDV not found!!');
        }
        
        $rdv->annuler();

        $this->repository->updateStatus($rdv);

        return new RendezVousDTO($rdv->id, $rdv->date_heure_debut->format('d-m-Y H:i:s'), $rdv->date_heure_fin->format('d-m-Y H:i:s'), $rdv->statut, $rdv->duree, $rdv->motif_visite);
    }

    public function creer(CreateRdvDTO $dto): CreateRdvDTO {
        $this->validator->valider($dto);
        return $dto;
    }
}
