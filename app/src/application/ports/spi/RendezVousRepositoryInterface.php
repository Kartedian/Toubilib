<?php

namespace toubilib\application\ports\spi;

use toubilib\domain\entities\RendezVousEntity;
use DateTimeImmutable;

Interface RendezVousRepositoryInterface{

    public function getById(string $id): RendezVousEntity;

    public function updateStatus(RendezVousEntity $rdv);

    public function sauvegarder(RendezVousEntity $rdv):void;

    public function getByPraticienId(string $id): array;

    public function getByDates_IdPraticienId(string $id,  DateTimeImmutable $dateDebut, DateTimeImmutable $datefin): array;
}