<?php

namespace toubilib\application\ports\spi;

use toubilib\domain\entities\RendezVousEntity;

Interface RendezVousRepositoryInterface{

    public function getById(string $id): RendezVousEntity;

    public function updateStatus(RendezVousEntity $rdv);

    public function sauvegarder(RendezVousEntity $rdv):void;

}