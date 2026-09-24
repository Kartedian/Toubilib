<?php
use toubilib\adapters\http\action\AnnulerRdvAction;
use toubilib\application\ports\api\ServiceRendezVousInterface;

return [
    // Injection du Service mÃ©tier dans l'Action Slim 
    AnnulerRdvAction::class => \DI\autowire(AnnulerRdvAction::class)
];