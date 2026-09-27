<?php

namespace toubilib\adapters;

use PDO;

class ToubilibRepositoryManager
{
    private static ?self $instance = null;

    private PDO $pdo;

    private ?PraticienRepository $praticienRepository = null;
    private ?RendezVousRepository $rendezVousRepository = null;

    private function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function __clone() {}

    public static function getInstance(PDO $pdo = null): self
    {
        if (self::$instance === null) {
            if ($pdo === null) {
                #throw new \Exception();
            }
            self::$instance = new self($pdo);
        }
        return self::$instance;
    }

    public function getPraticienRepository(): PDOPraticienRepository
    {
        if ($this->praticienRepository === null) {
            $this->praticienRepository = new PDOPraticienRepository($this->pdo);
        }
        return $this->praticienRepository;
    }

    public function getRendezVousRepository(): PDORendezVousRepository
    {
        if ($this->rendezVousRepository === null) {
            $this->rendezVousRepository = new PDORendezVousRepository($this->pdo);
        }
        return $this->rendezVousRepository;
    }
}