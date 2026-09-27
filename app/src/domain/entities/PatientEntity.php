<?php

namespace toubilib\domain\entities;

use DateTimeImmutable;

class PatientEntity {
    public readonly string $id;

    public function __construct(
        public readonly string $nom,
        public readonly string $prenom,
        public readonly DateTimeImmutable $date_naissance,
        public readonly string $adresse,
        public readonly string $code_postal,
        public readonly string $ville,
        public readonly string $email,
        public readonly string $telephone,
    )
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
        $this->id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

}