<?php

namespace toubilib\domain\entities;

enum RdvStatus: int
{
    case ANNULER = -1;
    case NEUTRE = 0;
    case HONORER = 1;
    case IGNORER = 2;

    public static function fromName(string $nom): self
    {
        foreach (self::cases() as $status) {
            if ($status->name === $nom) {
                return $status;
            }
        }

        throw new \ValueError("Statut de rdv inconnu : $nom");
    }
}