<?php
namespace toubilib\adapters\http\action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\application\ports\api\ServicePraticienInterface;

class GetAgendaPraticienAction
{
    public function __construct(
        private readonly ServicePraticienInterface $service
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        try {
            $rdvsDTO = $this->service->getAgenda($id);
            
            $rdvsFormates = [];
            foreach ($rdvsDTO as $rdv) {
                $rdvsFormates[] = [
                    "rendez_vous" => [
                        "id" => $rdv->id,
                        "date_debut" => $rdv->dateHeure->format('d/m/Y H:i'),
                        "etat" => $rdv->etat,
                    ],
                    "links" => [
                        "self" => ["href" => "/rdvs/" . $rdv->id . "/"]
                    ]
                ];
            }

            $reponseFormattee = [
                "type" => "collection",
                "agenda" => $rdvsFormates,
                "links" => [
                    "self" => ["href" => "/praticiens/" . $id . "/agenda"],
                    "praticien" => ["href" => "/praticiens/" . $id . "/"]
                ]
            ];

            $response->getBody()->write(json_encode($reponseFormattee));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                "message" => "404 Not Found",
                "exception" => [["message" => $e->getMessage()]]
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}