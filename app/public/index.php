<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use toubilib\adapters\http\action\CreerRdvAction;
use toubilib\adapters\http\action\GetRdvDetailAction;
use toubilib\adapters\http\action\StatusRdvAction;

/* application boostrap */
$appli = require_once __DIR__ . '/../config/bootstrap.php';

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/settings.php');
$builder->addDefinitions(__DIR__ . '/../config/services.php');
$builder->addDefinitions(__DIR__ . '/../config/api.php');

$container = $builder->build();

$app = AppFactory::createFromContainer($container);
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();


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

$app->get('/praticiens/{id}', \toubilib\adapters\http\action\GetPraticienByIdAction::class)->setName('praticien-detail');

$app->get('/praticiens/{id}/agenda', \toubilib\adapters\http\action\GetAgendaPraticienAction::class);

$app->get('/praticiens/{id}/rdvs', \toubilib\adapters\http\action\GetCreneauxPraticienAction::class)->setName('rdvsPra-list');

$app->group('/rdvs', function (RouteCollectorProxy $group) {
    //$group->get('', GetPanierAction::class)
    //    ->setName('rdvs-list');
    $group->get('/{id}', GetRdvDetailAction::class)
        ->setName('rdv-detail');
    $group->post('', CreerRdvAction::class)
        ->setName('add-rdv');
    $group->patch('/{id}/annuler', StatusRdvAction::class)
        ->setName('rdv-annuler')
        ->setArgument('statut', 'ANNULER');
    $group->patch('/{id}/honorer', StatusRdvAction::class)
        ->setName('rdv-honorer')
        ->setArgument('statut', 'HONORER');
    $group->patch('/{id}/ignorer', StatusRdvAction::class)
        ->setName('rdv-ignorer')
        ->setArgument('statut', 'IGNORER');
});


$app->run();