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



    
}