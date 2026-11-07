<?php

namespace toubilib\adapters;

use DateTimeImmutable;
use Override;
use PDO;
use toubilib\domain\entities\RendezVousEntity;
use toubilib\application\ports\spi\RendezVousRepositoryInterface;
use toubilib\domain\entities\RdvStatus;

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
            RdvStatus::from($row['status']),
            (int)$row['duree'],
            new DateTimeImmutable($row['date_heure_fin']),
            new DateTimeImmutable($row['date_creation']),
            $row['motif_visite']
        );
    }

    #[Override]
    public function updateStatus(RendezVousEntity $rdv)
    {
        $stmt = $this->pdo->prepare('UPDATE rdv SET status = :status WHERE id = :id');
        $stmt->execute([
            'status' => $rdv->status->value,
            'id' => $rdv->id
        ]);
    }

    #[Override]
    public function sauvegarder(RendezVousEntity $rdv): void
    {
        $stmt = $this->pdo->prepare("
        insert into rdv values
        (:id, :id_praticien, :id_patient, :date_debut, :status, :duree, :date_fin, :date_creation, :motif)");

        $stmt->execute([
            'id'=>$rdv->id,
            'id_praticien'=>$rdv->praticien_id,
            'id_patient'=>$rdv->patient_id,
            'date_debut'=>$rdv->date_heure_debut->format('Y-m-d H:i:s'),
            'status'=>$rdv->status->value,
            'duree'=>$rdv->duree,
            'date_fin'=>$rdv->date_heure_fin->format('Y-m-d H:i:s'),
            'date_creation'=>$rdv->date_creation->format('Y-m-d H:i:s'),
            'motif'=>$rdv->motif_visite
        ]);
    }

    public function getByPraticienId(string $id): array
    {
        $stmt = $this->pdo->prepare(
            'Select *
            from rdv
            where praticien_id = :id'
        );
        $stmt->execute(['id' => $id]);

        $rdvs = [];
        
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $rdvs[] = new RendezVousEntity(
                $row['id'],
                $row['praticien_id'],
                $row['patient_id'],
                new DateTimeImmutable($row['date_heure_debut']),
                RdvStatus::from($row['status']),
                (int)$row['duree'],
                new DateTimeImmutable($row['date_heure_fin']),
                new DateTimeImmutable($row['date_creation']),
                $row['motif_visite']
            );
        }

        return $rdvs;
    }

    public function getByDates_IdPraticienId(string $id, DateTimeImmutable $dateDebut, DateTimeImmutable $datefin): array
    {
        $stmt = $this->pdo->prepare(
            'Select *
            from rdv
            where praticien_id = :id and date_heure_debut between :debut and :fin'
        );
        $stmt->execute([
            'id' => $id,
            'debut' => $dateDebut->format('Y-m-d H:i:s'),
            'fin' => $datefin->format('Y-m-d H:i:s')
        ]);

        $rdvs = [];
        
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $rdvs[] = new RendezVousEntity(
                $row['id'],
                $row['praticien_id'],
                $row['patient_id'],
                new DateTimeImmutable($row['date_heure_debut']),
                RdvStatus::from($row['status']),
                (int)$row['duree'],
                new DateTimeImmutable($row['date_heure_fin']),
                new DateTimeImmutable($row['date_creation']),
                $row['motif_visite']
            );
        }

        return $rdvs;
    }
}