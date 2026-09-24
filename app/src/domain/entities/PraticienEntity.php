<?php

namespace toubilib\domain\entities;


class PraticienEntity{
    public function __construct(
        public readonly string $id,
        public readonly string $nom,
        public readonly string $prenom,
        public readonly string $ville,
        public readonly string $email,
        public readonly string $telephone,
        public readonly int $specialite_id,
        public readonly string $structure_id,
        public readonly string $rpps_id,
        public readonly bool $organisation,
        public readonly bool $nouveau_patient,
        public readonly string $titre
    )
    {}
}