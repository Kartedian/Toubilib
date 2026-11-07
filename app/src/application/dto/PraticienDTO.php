<?php

namespace toubilib\application\dto;

class PraticienDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $nom,
        public readonly string $prenom,
        public readonly string $specialite
    ) {}
}