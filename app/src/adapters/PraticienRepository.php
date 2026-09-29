<?php

namespace toubilib\adapters;

use toubilib\application\ports\spi\PraticienRepositoryInterface;
use toubilib\domain\entities\PraticienEntity;
use toubilib\domain\entities\RendezVousEntity;

use PDO;
use Exception;

class PraticienRepository implements PraticienRepositoryInterface 
{
    private PDO $pdo;

    public function __construct(PDO $pdo) 
    {
        $this->pdo = $pdo;
    }

    public function getById(string $id):?PraticienEntity{
        $stmt = $this->pdo->prepare("SELECT * FROM praticien WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $praticienData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$praticienData) {
            throw new Exception("Praticien introuvable dans la base de données.");
        }

        $p = new PraticienEntity(
            $praticienData['id'],
            $praticienData['nom'],
            $praticienData['prenom'],
            $praticienData['ville'],
            $praticienData['email'],
            $praticienData['telephone'],
            $praticienData['specialite_id'],
            $praticienData['structure_id'],
            $praticienData['rpps_id'],
            $praticienData['organisation'],
            $praticienData['nouveau_patient'],
            $praticienData['titre']
        );

        return $p;
    }

    public function chargerPraticienAvecRdvsDuJour(string $idPraticien, \DateTime $dateRDV): PraticienEntity 
    {
        $p = $this->getById($idPraticien);

        $dateJour = $dateRDV->format('Y-m-d');
        
        $stmtRdvs = $this->pdo->prepare("
            SELECT * FROM rdv 
            WHERE praticien_id = :id 
            AND DATE(date_heure_debut) = :date_jour
        ");
        
        $stmtRdvs->execute([
            'id' => $idPraticien,
            'date_jour' => $dateJour
        ]);
        
        $rdvsData = $stmtRdvs->fetchAll(PDO::FETCH_ASSOC);

        $rdvsObjects = [];
        foreach ($rdvsData as $row) {
            $rdvsObjects[] = new RendezVousEntity(
                $row['id'],
                $row['praticien_id'],
                $row['patient_id'],
                new \DateTime($row['date_heure_debut']),
                (int)$row['statut'],
                (int)$row['duree'],
                new \DateTime($row['date_heure_fin']),
                $row['date_creation'],
                $row['motif']
            );
        }

        $p->chargerRDVS($rdvsObjects);

        return $p;
    }
}