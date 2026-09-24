<?php

namespace toubilib\application\dto;

class RendezVousDTO{
    public function __construct(
        public readonly string $id,
        public readonly string $date_heure_debut,
        public readonly string $date_heure_fin,
        public readonly int $statut,
        public readonly int $duree,
        public readonly string $motif_visite
    ) {}
}