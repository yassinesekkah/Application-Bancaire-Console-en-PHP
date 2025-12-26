<?php

class CompteEpargne extends Compte
{
    public function deposit(float $amount): void
    {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif et numérique");
        }
        $soldeAvant = $this->getSolde();

        $soldeApres = $soldeAvant + $amount;

        $this->setSolde($soldeApres);

        echo "Dépôt : $amount DH, Nouveau solde: $soldeApres DH <br>";
    }

    public function withdraw(float $amount): void
    {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif et numérique");
        }
        //hna makayench l9adia dyal ti7 3la 0 donc khdam wsafi 
        $soldeAvant = $this -> getSolde();

        if($amount > $soldeAvant){
            throw new InvalidArgumentException("Impossible de retirer $amount DH, solde disponible : $soldeAvant DH");
        }
        $soldeApres = $soldeAvant - $amount;

        $this -> setSolde($soldeApres);
        echo "Retrait : $amount DH, Nouveau solde : $soldeApres DH <br>";
    }
}
