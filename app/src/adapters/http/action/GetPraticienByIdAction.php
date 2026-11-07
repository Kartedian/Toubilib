<?php
namespace toubilib\adapters\http\action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\application\ports\api\ServicePraticienInterface;

class GetPraticienByIdAction
{
    public function __construct(
        private readonly ServicePraticienInterface $service
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        try {
            $dto = $this->service->getPraticienById($id);

            $reponseFormattee = [
                "type" => "ressource",
                "praticien" => [
                    "id" => $dto->id,
                    "nom" => $dto->nom,
                    "prenom" => $dto->prenom,
                    "specialite" => $dto->specialite
                ],
                "links" => [
                    "self" => ["href" => "/praticiens/" . $id . "/"],
                    "agenda" => ["href" => "/praticiens/" . $id . "/agenda"],
                    "creneaux" => ["href" => "/praticiens/" . $id . "/rdvs"]
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