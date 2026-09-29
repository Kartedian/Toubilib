<?php

namespace toubilib\application\dto;

use DateTime;

class CreateRdvDTO {

    
    public function __construct (
         public readonly string $idPraticien,
         public readonly string $idPatient,
         public readonly string $motif,
         public readonly DateTime $dateHeure,
    )
    {}
}