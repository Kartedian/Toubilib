<?php
use toubilib\adapters\http\action\AnnulerRdvAction;

return [
    // Injection du Service mÃ©tier dans l'Action Slim 
    AnnulerRdvAction::class => \DI\autowire(AnnulerRdvAction::class)
];