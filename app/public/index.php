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

$app->run();