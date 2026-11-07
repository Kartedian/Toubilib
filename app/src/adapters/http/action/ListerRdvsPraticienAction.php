<?php
namespace toubilib\adapters\http\action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use Slim\Routing\RouteContext;
use toubilib\application\dto\RendezVousDTO;
use DateTimeImmutable;

class ListerRdvsPraticienAction
{
    public function __construct(
        private readonly ServiceRendezVousInterface $service
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $params = $request->getQueryParams();

        $debut = trim((string) ($params['debut'] ?? ''));
        $fin = trim((string) ($params['fin'] ?? ''));
        $debut = $debut === '' ? null : $debut;
        $fin = $fin === '' ? null : $fin;

        if ($debut !== null & $fin !== null) {
            $dateDebut = DateTimeImmutable::createFromFormat('!d-m-Y', $debut);
            if ($dateDebut === false || $dateDebut->format('Y-m-d') !== $debut) {
                $debut = null;
            }

            $dateFin = DateTimeImmutable::createFromFormat('!d-m-Y', $fin);
            if ($dateFin === false || $dateFin->format('Y-m-d') !== $fin) {
                $fin = null;
            }
        }

        try {
            if ($debut !== null & $fin !== null) {
                $dto = $this->service->getRdvsByDates_IdPraticienId($id, $dateDebut, $dateFin);
            }
            else {
                $dto = $this->service->getRdvsByPraticienId($id);
            }
            
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                "message" => "404 Not Found",
                "exception" => [["message" => $e->getMessage()]]
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $rdvsJson = array_map(
            fn(RendezVousDTO $rdv) => [
                ...$rdv->jsonSerialize(),
                'links' => [
                    'self' => ['href' => $routeParser->urlFor('rdv-detail', ['id' => $rdv->id])],
                ],
            ],
            $dto
        );

        $reponseFormattee = [
            'rdvs' => $rdvsJson,
            'links' => [
                'self' => ['href' => $routeParser->urlFor('rdvs-list')],
            ],
        ];

        $response->getBody()->write(json_encode($reponseFormattee));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}