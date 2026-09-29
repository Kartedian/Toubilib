<?php

namespace toubilib\adapters;

use Override;
use PDO;
use toubilib\application\ports\spi\PatientRepositoryInterface;
use toubilib\domain\entities\PatientEntity;

class PatientRepository implements PatientRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo
    )
    {}

    #[Override]
    public function getById(string $id): ?PatientEntity
    {
        $stmt = $this->pdo->prepare('select * from patient id = :id');
        $stmt->execute(['id' => $id]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$patient) {
            #throw new Exception("Patient introuvable dans la base de données.");
        }

        return new PatientEntity(
            $patient['id'],
            $patient['nom'],
            $patient['prenom'],
            $patient['date_naissance'],
            $patient['adresse'],
            $patient['code_postal'],
            $patient['ville'],
            $patient['email'],
            $patient['telephone']
        );
    }
}