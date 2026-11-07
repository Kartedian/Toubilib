<?php
namespace toubilib\application\usecases;

use toubilib\application\dto\PraticienDTO;
use toubilib\application\ports\api\ServicePraticienInterface;
use toubilib\application\ports\spi\PraticienRepositoryInterface;
use toubilib\application\ports\spi\RendezVousRepositoryInterface
use Exception;

class ServicePraticien implements ServicePraticienInterface {
    
    public function __construct(
        private readonly PraticienRepositoryInterface $praticienRepository,
        private readonly RendezVousRepositoryInterface $rdvRepository
    ) {}

    public function getPraticienById(string $id): PraticienDTO
    {
        $praticien = $this->praticienRepository->getById($id);
        
        if (!$praticien) {
            throw new Exception("Praticien $id introuvable");
        }

        return new PraticienDTO(
            id: $praticien->id,
            nom: $praticien->nom,
            prenom: $praticien->prenom,
            specialite: $praticien->specialite
        );
    }

    public function getAgenda(string $id): array
    {
        
        $praticien = $this->praticienRepository->getById($id);
        if (!$praticien) {
            throw new \Exception("Praticien $id introuvable");
        }

        
        $rdvs = $this->rdvRepository->getRdvsByPraticien($id);
        
        $rdvsDTO = [];
        foreach ($rdvs as $rdv) {
            $rdvsDTO[] = new \toubilib\application\dto\RendezVousDTO($rdv); 
        }
        
        return $rdvsDTO;
    }
}