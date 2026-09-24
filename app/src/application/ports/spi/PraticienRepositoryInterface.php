<?php

namespace toubilib\application\ports\spi;

use toubilib\domain\entities\PraticienEntity;

interface PraticienRepositoryInterface {
    public function getById(string $id): ?Praticien;
}