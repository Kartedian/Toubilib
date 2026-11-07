<?php

namespace toubilib\application\ports\api;

use toubilib\application\dto\RendezVousDTO;
use toubilib\application\dto\CreateRdvDTO;
use DateTimeImmutable;

interface ServiceRendezVousInterface{

    public function annuler(string $id): RendezVousDTO;
    public function creer(CreateRdvDTO $dto): RendezVousDTO;
    public function getRdvsByPraticienId(string $id): array;
    public function getRdvsByDates_IdPraticienId(string $id, DateTimeImmutable $dateDebut, DateTimeImmutable $datefin): array;
}