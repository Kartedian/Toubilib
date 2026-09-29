<?php

namespace toubilib\adapters\http\action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\application\ports\api\ServiceRendezVousInterface;
use toubilib\application\dto\CreateRdvDTO;

class CreerRdvAction {
    public function __construct(
        private readonly ServiceRendezVousInterface $service,
    ) {}

    public function __invoke(Request $request, Response $response, array $args) : Response
    {
        $data = $request->getParsedBody();

        try {
            $dto = CreateRdvDTO::fromArray($data);

            $rdvCree = $this->service->creer($dto);

            $response->getBody()->write(json_encode(['message' => 'RDV créé avec succès']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }
}