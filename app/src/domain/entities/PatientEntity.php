<?php

namespace toubilib\domain\entities;

use DateTimeImmutable;
use toubilib\domain\shared\UuidGenerator;

class PatientEntity {
    use UuidGenerator;
    public function __construct(
        public readonly string $id,
        public readonly string $nom,
        public readonly string $prenom,
        public readonly DateTimeImmutable $date_naissance,
        public readonly string $adresse,
        public readonly string $code_postal,
        public readonly string $ville,
        public readonly string $email,
        public readonly string $telephone,
    )
    {}

}