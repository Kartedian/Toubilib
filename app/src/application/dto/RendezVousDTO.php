<?php

namespace toubilib\application\dto;

use Override;
use toubilib\domain\entities\RendezVousEntity;

class RendezVousDTO implements \JsonSerializable {
    public function __construct(
        public readonly string $id,
        public readonly string $date_heure_debut,
        public readonly string $date_creation,
        public readonly string $status,
        public readonly int $duree,
        public readonly string $motif_visite
    ) {}

    public static function fromEntity(RendezVousEntity $rdv): self
    {
        return new self(
            $rdv->getId(),
            $rdv->getDate_heure_debut()->format('d-m-Y H:i:s'),
            $rdv->getDateCreation()->format('d-m-Y H:i:s'),
            $rdv->getStatus()->name,
            $rdv->getDuree(),
            $rdv->getMotif()
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,                  
            'date_heure_debut' => $this->date_heure_debut,
            'date_creation' => $this->date_creation,
            'status' => $this->status,
            'duree' => $this->duree,
            'motif' => $this->motif_visite
        ];
    }
}