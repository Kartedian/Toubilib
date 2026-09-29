<?php 

namespace toubilib\application\validators;

use toubilib\application\ports\spi\PraticienRepositoryInterface;
use toubilib\application\ports\spi\PatientRepositoryInterface;
use toubilib\application\dto\CreateRdvDTO;

class RdvValidator {
    public function __construct(
        private readonly PraticienRepositoryInterface $praticienRepo,
        private readonly PatientRepositoryInterface $patientRepo
    )
    {}

    public function valider(CreateRdvDTO $dto): void
    {
        $praticien = $this->praticienRepo->getById($dto->idPraticien);

        if ($praticien == null) {
            throw new \Exception("Le praticien demandé n'existe pas");
        }

        $patient = $this->patientRepo->getById($dto->idPatient);

        if($patient == null) {
            throw new \Exception("Le patient demandé n'existe pas");
        }

    }
}