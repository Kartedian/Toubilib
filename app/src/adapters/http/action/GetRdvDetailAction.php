<?php
namespace toubilib\adapters\http\action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use Slim\Routing\RouteContext;
use toubilib\domain\entities\RdvStatus;

class GetRdvDetailAction
{
    public function __construct(
        private readonly ServiceRendezVousInterface $service
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        try {
            $dto = $this->service->getById($id);
            
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                "message" => "404 Not Found",
                "exception" => [["message" => $e->getMessage()]]
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $links = [
            'self' => ['href' => $routeParser->urlFor('rdv-detail', ['id' => $dto->id])],
            'praticien' => ['href' => $routeParser->urlFor('praticien-detail', ['id' => $dto->praticien_id])],
            'patient' => ['href' => "..."],
        ];

        if ($dto->status === RdvStatus::NEUTRE->name) {
            $links['annuler'] = ['href' => $routeParser->urlFor('rdv-annuler', ['id' => $dto->id]), 'method' => 'PATCH'];
            $links['honorer'] = ['href' => $routeParser->urlFor('rdv-honorer', ['id' => $dto->id]), 'method' => 'PATCH'];
            $links['ignorer'] = ['href' => $routeParser->urlFor('rdv-ignorer', ['id' => $dto->id]), 'method' => 'PATCH'];
        }

        $reponseFormattee = [
            'type' => 'ressource',
            'rendez_vous' => $dto,
            'links' => $links,
        ];

        $response->getBody()->write(json_encode($reponseFormattee));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}