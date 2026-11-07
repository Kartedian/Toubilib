<?php

use Psr\Container\ContainerInterface;
use toubilib\adapters\http\action\AnnulerRdvAction;
use toubilib\adapters\http\action\CreerRdvAction;
use toubilib\adapters\http\action\GetPraticienByIdAction;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use toubilib\application\ports\api\ServicePraticienInterface;
use toubilib\adapters\http\action\GetAgendaPraticienAction;
use toubilib\adapters\http\action\GetCreneauxPraticienAction;
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

    GetAgendaPraticienAction::class => function (\Psr\Container\ContainerInterface $c) {
        return new \toubilib\adapters\http\action\GetAgendaPraticienAction(
            $c->get(\toubilib\application\ports\api\ServicePraticienInterface::class)
        );
    },

     GetCreneauxPraticienAction::class => function (\Psr\Container\ContainerInterface $c) {
        return new \toubilib\adapters\http\action\GetCreneauxPraticienAction(
            $c->get(\toubilib\application\ports\api\ServicePraticienInterface::class)
        );
    },

    ServicePraticienInterface::class => function (\Psr\Container\ContainerInterface $c) {
        return new \toubilib\application\usecases\ServicePraticien(
            $c->get(\toubilib\application\ports\spi\PraticienRepositoryInterface::class),
            $c->get(\toubilib\application\ports\spi\RendezVousRepositoryInterface::class)
        );
    }
];