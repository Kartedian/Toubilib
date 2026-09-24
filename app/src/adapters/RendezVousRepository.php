<?php

namespace toubilib\adapters;

use DateTime;
use Override;
use PDO;
use toubilib\domain\entities\RendezVousEntity;
use toubilib\application\ports\spi\RendezVousRepositoryInterface;

class RendezVousRepository implements RendezVousRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo
    )
    {}

    #[Override]
    public function getById(string $id): RendezVousEntity
    {
        $stmt = $this->pdo->prepare('select * from rdv where id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($row)){
            throw new \Exception("Rendez-vous not found");
        }

        return new RendezVousEntity(
            $row['id'],
            new DateTime($row['date_heure_debut']),
            new DateTime($row['date_heure_fin']),
            new DateTime($row['date_creation']),
            (int)$row['statut'],
            (int)$row['duree'],
            $row['motif_visite']
        );
    }

    #[Override]
    public function updateStatus(RendezVousEntity $rdv)
    {
        $stmt = $this->pdo->prepare('UPDATE rdv SET statut = :statut WHERE id = :id');
        $stmt->execute([
            'statut' => $rdv->statut,
            'id' => $rdv->id
        ]);
    }
}