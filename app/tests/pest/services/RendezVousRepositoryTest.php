<?php

namespace tests\pest\services;

use PHPUnit\Framework\TestCase;
use toubilib\adapters\RendezVousRepository;
use PDO;

class RendezVousRepositoryTest extends TestCase {

    public function test_get_by_id_ritorna_entita_idratata() {
        $pdo = new PDO("pgsql:host=toubilib.db;dbname=toubilib", "root", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->beginTransaction();

        $pdo->exec("INSERT INTO rdv (id, praticien_id, patient_id, date_heure_debut, status, duree, date_heure_fin, date_creation, motif_visite) 
                    VALUES ('id-test-123', 'id-test-123', 'id-test-123', '2026-10-01 10:00:00', 0, 30, '2026-10-01 10:00:00', '2026-10-01 10:00:00', 'Consultation')");

        $repo = new RendezVousRepository($pdo);
        $rdv = $repo->getById('id-test-123');

        $this->assertNotEmpty($rdv, 'Bad!!! SO BAD, MY BOY!!!');
        $pdo->rollBack();
    }
}