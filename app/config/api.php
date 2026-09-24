<?php
use toubilib\adapters\http\action\AnnulerRdvAction;
use toubilib\application\ports\api\ServiceRendezVousInterface;

return [
    // Injection du Service mÃ©tier dans l'Action Slim 
    AnnulerRdvAction::class => function(\Psr\Container\ContainerInterface $c) {
        return new AnnulerRdvAction($c->get(ServiceRendezVousInterface::class));
    }
];