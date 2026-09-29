<?php

namespace toubilib\adapters;

use PDO;

class ToubilibRepositoryManager
{
    private static ?self $instance = null;

    private PDO $pdo;

    private ?PraticienRepository $praticienRepository = null;
    private ?RendezVousRepository $rendezVousRepository = null;
    private ?PatientRepository $patientRepository = null;

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

    public function getPatientRepository(){
        if($this->patientRepository === null){
            $this->patientRepository = new PatientRepository($this->pdo);
        }

        return $this->patientRepository;
    }

    public function getPraticienRepository(): PraticienRepository
    {
        if ($this->praticienRepository === null) {
            $this->praticienRepository = new PraticienRepository($this->pdo);
        }
        return $this->praticienRepository;
    }

    public function getRendezVousRepository(): RendezVousRepository
    {
        if ($this->rendezVousRepository === null) {
            $this->rendezVousRepository = new RendezVousRepository($this->pdo);
        }
        return $this->rendezVousRepository;
    }
}