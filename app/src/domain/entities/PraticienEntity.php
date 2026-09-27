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
    public readonly string $id;

    public function __construct(
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
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
        $this->id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

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
}