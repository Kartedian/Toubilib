<?php

namespace toubilib\application\ports\spi;

use toubilib\domain\entities\PatientEntity;

interface PatientRepositoryInterface {
    public function getById(string $id): ?Patient;
}