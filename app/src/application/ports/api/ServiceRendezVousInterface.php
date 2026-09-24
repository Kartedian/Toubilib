<?php

namespace toubilib\application\ports\api;

use toubilib\application\dto\RendezVousDTO;
use toubilib\application\dto\CreateRdvDTO;

interface ServiceRendezVousInterface{

    public function annuler(string $id): RendezVousDTO;
    public function creer(CreateRdvDTO $dto): CreateRdvDTO;
    
}