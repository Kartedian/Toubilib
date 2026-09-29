<?php

namespace toubilib\application\dto;

use DateTimeImmutable;

class CreateRdvDTO {

    
    public function __construct (
         public readonly string $idPraticien,
         public readonly string $idPatient,
         public readonly string $motif,
         public readonly DateTimeImmutable $dateHeure,
    )
    {}

    public static function fromArray(array $data): self {
        if (!isset($data['idPraticien']) || trim($data['idPraticien']) === '') {
            throw new \InvalidArgumentException("L'identifiant du praticien est manquant ou vide.");
        }

        if (!isset($data['idPatient']) || trim($data['idPatient']) === '') {
            throw new \InvalidArgumentException("L'identifiant du patient est manquant ou vide.");
        }

        if (!isset($data['motif']) || trim($data['motif']) === '') {
            throw new \InvalidArgumentException("Le motif est manquant ou vide.");
        }

        if (!isset($data['dateHeure'])) {
            throw new \InvalidArgumentException("La date et l'heure sont manquantes.");
        }

        try {
            $date = new DateTimeImmutable($data['dateHeure']);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Le format de la date est invalide.");
        }

     return new self(
            $data['idPraticien'],
            $data['idPatient'],
            $data['motif'],
            $date
            );
    }
}