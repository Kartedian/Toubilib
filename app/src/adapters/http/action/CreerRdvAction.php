<?php

namespace toubilib\adapters\http\action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use toubilib\application\dto\CreateRdvDTO;
use Slim\Routing\RouteContext;

class CreerRdvAction {
    public function __construct(
        private readonly ServiceRendezVousInterface $service,
    ) {}

    public function __invoke(Request $request, Response $response, array $args) : Response
    {
        $data = $request->getParsedBody();

        if (!is_array($data)) {
            $response->getBody()->write(json_encode(['error' => 'Corps de requête JSON attendu']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        try {
            $dto = CreateRdvDTO::fromArray($data);

            $rdvCree = $this->service->creer($dto);

            $routeParser = RouteContext::fromRequest($request)->getRouteParser();
            $url = $routeParser->urlFor('rdv-detail', ['id' => $rdvCree->id]);

            $response->getBody()->write(json_encode($rdvCree));
            return $response->withHeader('Content-Type', 'application/json')->withHeader('Location', $url)->withStatus(201);
        } 
        catch (\InvalidArgumentException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}