<?php
namespace toubilib\adapters\http\action;

use toubilib\application\ports\api\ServiceRendezVousInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AnnulerRdvAction
{
    public function __construct(private ServiceRendezVousInterface $service) {}

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        
        try {
            $dto = $this->service->annuler($id);
            $response->getBody()->write(json_encode($dto));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            
        } catch (\DomainException $e) { // Erreur métier (ex: RDV passé)
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            
        } catch (\Exception $e) { // Erreur non trouvée
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    }
}