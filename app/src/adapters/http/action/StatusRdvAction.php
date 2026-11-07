<?php

namespace toubilib\adapters\http\action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use toubilib\domain\entities\RdvStatus;

class StatusRdvAction
{
    public function __construct(
        private readonly ServiceRendezVousInterface $service
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        try {
            $statut = RdvStatus::fromName($args['statut'] ?? '');
        } catch (\ValueError $e) {
            return $this->json($response, ['error' => $e->getMessage()], 400);
        }

        try {
            $dto = $this->service->setStatusRdv($id, $statut);
        } catch (\DomainException $e) {
            return $this->json($response, ['error' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            return $this->json($response, ['error' => $e->getMessage()], 404);
        }

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        return $this->json($response, [
            'type' => 'ressource',
            'rendez_vous' => $dto,
            'links' => [
                'self' => ['href' => $routeParser->urlFor('rdv-detail', ['id' => $dto->id])],
            ],
        ], 200);
    }

    private function json(Response $response, array $data, int $status): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
