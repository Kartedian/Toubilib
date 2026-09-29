<?php

use Psr\Container\ContainerInterface;
use toubilib\adapters\http\action\AnnulerRdvAction;
use toubilib\adapters\http\action\CreerRdvAction;
use toubilib\application\ports\api\ServiceRendezVousInterface;

return [

    AnnulerRdvAction::class => function (ContainerInterface $c) {
        $service = $c->get(ServiceRendezVousInterface::class);
        return new AnnulerRdvAction($service);
    }, 

    CreerRdvAction::class => function (ContainerInterface $c) {
        $service = $c->get(ServiceRendezVousInterface::class); 
        return new CreerRdvAction($service);
    }
];