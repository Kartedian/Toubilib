<?php
namespace toubilib\application\usecases;

use toubilib\application\dto\PraticienDTO;
use toubilib\application\ports\api\ServicePraticienInterface;
use toubilib\application\ports\spi\PraticienRepositoryInterface;
use Exception;

class ServicePraticien implements ServicePraticienInterface {
    
    public function __construct(
        private readonly PraticienRepositoryInterface $praticienRepository
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
}