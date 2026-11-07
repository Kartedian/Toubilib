<?php

namespace toubilib\application\ports\api;

use toubilib\application\dto\RendezVousDTO;
use toubilib\application\dto\CreateRdvDTO;
use DateTimeImmutable;
use toubilib\application\dto\RendezVousDetailDTO;
use toubilib\domain\entities\RdvStatus;

interface ServiceRendezVousInterface{

    public function creer(CreateRdvDTO $dto): RendezVousDetailDTO;

    public function getById(string $id): RendezVousDetailDTO;

    public function getRdvsByPraticienId(string $id): array;

    public function getRdvsByDates_IdPraticienId(string $id, DateTimeImmutable $dateDebut, DateTimeImmutable $datefin): array;

    public function setStatusRdv(string $id, RdvStatus $status): RendezVousDetailDTO;
}