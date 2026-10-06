<?php

namespace toubilib\adapters;

use DateTimeImmutable;
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
            $row['praticien_id'],
            $row['patient_id'],
            new DateTimeImmutable($row['date_heure_debut']),
            (int)$row['statut'],
            (int)$row['duree'],
            new DateTimeImmutable($row['date_heure_fin']),
            new DateTimeImmutable($row['date_creation']),
            $row['motif_visite']
        );
    }

    #[Override]
    public function updateStatus(RendezVousEntity $rdv)
    {
        $stmt = $this->pdo->prepare('UPDATE rdv SET statut = :statut WHERE id = :id');
        $stmt->execute([
            'statut' => $rdv->status,
            'id' => $rdv->id
        ]);
    }

    #[Override]
    public function sauvegarder(RendezVousEntity $rdv): void
    {
        $stmt = $this->pdo->prepare("
        insert into rdv values
        :id, :id_praticien, :id_patient, :date_debut, :status, :duree, :date_fin, :date_creation, :motif");

        $stmt->execute([
            'id'=>$rdv->id,
            'id_praticien'=>$rdv->praticien_id,
            'id_patient'=>$rdv->patient_id,
            'date_debut'=>$rdv->date_heure_debut,
            'status'=>$rdv->status,
            'duree'=>$rdv->duree,
            'date_fin'=>$rdv->date_heure_fin,
            'date_creation'=>$rdv->date_creation,
            'motif'=>$rdv->motif_visite
        ]);
    }
}