<?php

namespace toubilib\adapters\http\action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\application\ports\api\ServiceRendezVousInterface;

class AnnulerRdvAction
{
    public function __construct(
        private readonly ServiceRendezVousInterface $service
    ) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        
        try {
            $dto = $this->service->annuler($id);
            
            $reponseFormattee = [
                "type" => "ressource",
                "rendez_vous" => [
                    "id" => $dto->id,
                    "date_debut" => $dto->dateHeure->format('d/m/Y H:i'),
                    "etat" => "ANNULE",
                    "praticien_id" => $dto->idPraticien,
                    "patient_id" => $dto->idPatient,
                    "duree" => $dto->duree ?? 20,
                    "motif_visite" => $dto->motif,
                ],
                "links" => [
                   
                    "self" => ["href" => "/rdvs/" . $id . "/"],
                    "praticien" => ["href" => "/praticiens/" . $dto->idPraticien . "/"],
                    "patient" => ["href" => "/patients/" . $dto->idPatient . "/"]
                    
                ]
            ];

           
            $response->getBody()->write(json_encode($reponseFormattee));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            
        } catch (\DomainException $e) { 
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            
        } catch (\Exception $e) { 
            
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}