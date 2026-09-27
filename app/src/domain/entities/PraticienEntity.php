<?php

namespace toubilib\domain\entities;

use DateTime;

const HEURE_DEBUT_MATIN = '08:00';
const HEURE_FIN_SOIR = '18:00';

#   LUNDI = 1
#   MARDI = 2
#   MERCREDI = 3
#   JEUDI = 4
#   VENDREDI = 5


class PraticienEntity{
    private array $rdvs = [];

    public function __construct(
        public readonly string $id,
        public readonly string $nom,
        public readonly string $prenom,
        public readonly string $ville,
        public readonly string $email,
        public readonly string $telephone,
        public readonly int $specialite_id,
        public readonly string $structure_id,
        public readonly string $rpps_id,
        public readonly bool $organisation,
        public readonly bool $nouveau_patient,
        public readonly string $titre
    )
    {}

    public function verifierMotif(string $motif):bool{
        if($motif === 'CI' || $motif === 'C0' || $motif === 'CS'){
            return true;
        }
        return false;
    }

    public function calculDureeRDV(string $motif):int{
        if($this->verifierMotif($motif)){
            if($motif === 'CI')
                return 30;

            if($motif === 'C0')
                return 20;

            if($motif === 'CS')
                return 15;
        }
        #throw new MotifPasValable(); 
    }

    public function verifierDateRDV(DateTime $dateHeureDemande, string $motif):bool{
        $jour = (int) $dateHeureDemande->format('N');
        if( $jour === 6 || $jour === 7)
            return false;
        
        $heur = $dateHeureDemande->format('H:i');
        if($heur < HEURE_DEBUT_MATIN || $heur >= HEURE_FIN_SOIR)
            return false;
        
        $dateCopy = clone $dateHeureDemande;
        $duree = $this->calculDureeRDV($motif);
        $dateCopy->modify("+$duree minutes");

        $heureFin = $dateCopy->format('H:i');
        if($heureFin > HEURE_FIN_SOIR)
            return false;

        return true;
    }

    public function verifierDisponibilite(RendezVousEntity $newRDV):bool{
        foreach ($this->rdvs as $rdv){
            if($rdv->chevauche($newRDV))
                #throw new \Exception("Le praticien n'est pas disponible à cet horaire.");
        }
        return true;
    }

    public function chargerRDVS(array $rdvs):void{
        if(!empty($rdvs)){
            $this->rdvs = clone $rdvs;
        }
    }
}