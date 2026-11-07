<?php

use Psr\Container\ContainerInterface;
use toubilib\adapters\http\action\AnnulerRdvAction;
use toubilib\adapters\http\action\CreerRdvAction;
use toubilib\adapters\http\action\GetPraticienByIdAction;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use toubilib\application\ports\api\ServicePraticienInterface;
use toubilib\application\ports\spi\PraticienRepositoryInterface;
use toubilib\application\usecases\ServicePraticien;

return [


    AnnulerRdvAction::class => function (ContainerInterface $c) {
        $service = $c->get(ServiceRendezVousInterface::class);
        return new AnnulerRdvAction($service);
    }, 

    CreerRdvAction::class => function (ContainerInterface $c) {
        $service = $c->get(ServiceRendezVousInterface::class); 
        return new CreerRdvAction($service);
    },

    GetPraticienByIdAction::class => function (ContainerInterface $c) {
        $service = $c->get(ServicePraticienInterface::class);
        return new GetPraticienByIdAction($service);
    },


    ServicePraticienInterface::class => function (ContainerInterface $c) {
        $repository = $c->get(PraticienRepositoryInterface::class);
        return new ServicePraticien($repository);
    }
];