<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;

/* application boostrap */
$appli = require_once __DIR__ . '/../config/bootstrap.php';

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/settings.php');
$builder->addDefinitions(__DIR__ . '/../config/services.php');
$builder->addDefinitions(__DIR__ . '/../config/api.php');

$container = $builder->build();

$app = AppFactory::createFromContainer($container);


$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});


$app->post('/rendezvous', \toubilib\adapters\http\action\CreerRdvAction::class);


$app->post('/rdvs/{id}/annuler', \toubilib\adapters\http\action\AnnulerRdvAction::class);


$app->get('/praticiens/{id}', \toubilib\adapters\http\action\GetPraticienByIdAction::class);

$app->get('/praticiens/{id}/agenda', \toubilib\adapters\http\action\GetAgendaPraticienAction::class);


$app->run();