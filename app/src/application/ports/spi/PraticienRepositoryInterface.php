<?php

namespace toubilib\application\ports\spi;

use toubilib\domain\entities\PraticienEntity;
use DateTimeImmutable;

interface PraticienRepositoryInterface {
    public function getById(string $id):?PraticienEntity;

    public function chargerPraticienAvecRdvsDuJour(string $idPraticien, DateTimeImmutable $dateRDV): PraticienEntity;
}