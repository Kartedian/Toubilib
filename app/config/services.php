<?php

use Psr\Container\ContainerInterface;
use toubilib\adapters\RendezVousRepository;
use toubilib\application\ports\spi\RendezVousRepositoryInterface;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use toubilib\application\usecases\ServiceRendezVous;

return [
    PDO::class => function(ContainerInterface $c){
        $db = $c->get('settings')['db'];

        $dsn = $db['lib.driver'].":host=".$db['lib.host'].";dbname=".$db['lib.database'];
        $pdo = new PDO($dsn, $db['lib.username'], $db['lib.password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    },
    RendezVousRepositoryInterface::class => \DI\autowire(RendezVousRepository::class),
    ServiceRendezVousInterface::class => \DI\autowire(ServiceRendezVous::class)
];