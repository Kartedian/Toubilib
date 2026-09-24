<?php

namespace tests\pest\domaine\entities;

use PHPUnit\Framework\TestCase;
use toubilib\domain\entities\RendezVousEntity;
use DateTime;

class RendezVousEntityTest extends TestCase {

    public function test_annuler_rendez_vous_futur_change_statut() {
        $rdv = new RendezVousEntity('1', new DateTime('+1 day'), new DateTime('+1 day 1 hour'), new DateTime(), 0, 60, 'test');
        $rdv->annuler();
        $this->assertEquals(-1, $rdv->statut);
    }

    public function test_annuler_rendez_vous_passe_lance_exception() {
        $rdv = new RendezVousEntity('1', new DateTime('-1 day'), new DateTime('-1 day 1 hour'), new DateTime(), 0, 60, 'test');
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('error');
        $rdv->annuler();
    }
}