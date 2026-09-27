<?php

namespace toubilib\application\ports\spi;

use toubilib\domain\entities\PraticienEntity;

interface PraticienRepositoryInterface {
    public function chargerPraticienAvecRdvsDuJour(string $idPraticien, \DateTime $dateRDV): PraticienEntity;
}