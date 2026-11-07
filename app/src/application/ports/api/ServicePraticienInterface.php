<?php

namespace toubilib\application\ports\api;

use toubilib\application\dto\PraticienDTO; 

interface ServicePraticienInterface
{
    public function getPraticienById(string $id): PraticienDTO;
}