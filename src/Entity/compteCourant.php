<?php 

class compteCourant extends Compte 
{
    private float $fees = 5;

    public function deposit(float $amount): void
    {
        if($amount <= 0){
            throw new InvalidArgumentException("Le montant doit être positif");
        }

        $soldeAvant = $this -> getSolde();
        $soldeApres = $soldeAvant + $amount - $this -> fees;

        $this -> setSolde($soldeApres);
        echo "Dépôt : $amount DH, Frais : 5 DH, Nouveau solde: $soldeApres DH <br>";
    }

    public function withdraw(float $amount): void
    {
        if($amount <= 0){
            throw new InvalidArgumentException("Le montant doit être positif");
        }
        $soldeAvant = $this -> getSolde();
        $maxWithraw = $soldeAvant + 5000;
        
        if($amount > $maxWithraw){
            throw new InvalidArgumentException("le montant demandé dépasse le solde autorisé du compte");
        }
        $soldeApres = $soldeAvant - $amount;

        $this -> setSolde($soldeApres);

        echo "Retrait : $amount DH, Nouveau solde : $soldeApres DH <br>";
    }
}