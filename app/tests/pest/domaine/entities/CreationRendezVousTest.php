<?php

use PHPUnit\Framework\TestCase;
use toubilib\Domain\Entities\PraticienEntity;
use toubilib\Domain\Entities\PatientEntity;
use toubilib\Domain\Entities\RendezVousEntity;

class CreationRendezVousTest extends TestCase
{
    public function testVerifierMotif()
    {
        // Usa l'entità così come l'hai definita
        $praticien = new PraticienEntity('Ross', 'Astro', 'Paris', '1234@gmail.com', '123 456 7890', 1, 'house', 'a1b2c3', false, false, 'unemloyed');

        // Motivi validi
        $this->assertTrue($praticien->verifierMotif('CI'));
        $this->assertTrue($praticien->verifierMotif('C0'));
        $this->assertTrue($praticien->verifierMotif('CS'));

        // Motivo non valido
        $this->assertFalse($praticien->verifierMotif('INVALIDE'));
    }

    public function testCalculDureeRDV()
    {
        $praticien = new PraticienEntity('Ross', 'Astro', 'Paris', '1234@gmail.com', '123 456 7890', 1, 'house', 'a1b2c3', false, false, 'unemloyed');

        // Verifica le durate che hai impostato
        $this->assertEquals(30, $praticien->calculDureeRDV('CI'));
        $this->assertEquals(20, $praticien->calculDureeRDV('C0'));
        $this->assertEquals(15, $praticien->calculDureeRDV('CS'));
    }

    public function testValidationCreationRDVReussite()
    {
        // 1. Setup dei dati fittizi
        $praticien = new PraticienEntity('Ross', 'Astro', 'Paris', '1234@gmail.com', '123 456 7890', 1, 'house', 'a1b2c3', false, false, 'unemloyed');

        $patient = new PatientEntity('Specter', 'Arthur', new DateTime('now'), 'Street', '138', 'NYC', '1234@gmail.com', 'a1b2c3', '123 456 7890');
        
        $dateDemandee = new \DateTime('2026-09-28 10:00:00');
        $motif = 'CI'; 

        $rdv = RendezVousEntity::validation_creationRDV($praticien, $patient, $dateDemandee, $motif);

        $this->assertInstanceOf(RendezVousEntity::class, $rdv);
        
        $this->assertEquals(30, $rdv->duree); 
        
        $dateFinAttendue = new \DateTime('2026-09-28 10:30:00');
        $this->assertEquals($dateFinAttendue, $rdv->date_heure_fin);
    }
}