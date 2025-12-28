<?php

class CompteCourant extends Compte
{
    private float $fees = 5;

    public function deposit(float $amount): void
    {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif et numérique");
        }

        $soldeAvant = $this->getSolde();
        $soldeApres = $soldeAvant + $amount - $this->fees;

        $this->setSolde($soldeApres);
        echo "Dépôt : $amount DH, Frais : 5 DH, Nouveau solde: $soldeApres DH <br>";
    }

    public function withdraw(float $amount): void
    {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif et numérique");
        }

        $soldeAvant = $this->getSolde();
        $maxWithraw = $soldeAvant + 5000;

        if ($amount > $maxWithraw) {
            throw new InvalidArgumentException("Impossible de retirer $amount DH, solde disponible : $soldeAvant DH");
        }
        $soldeApres = $soldeAvant - $amount;

        $this->setSolde($soldeApres);

        echo "Retrait : $amount DH, Nouveau solde : $soldeApres DH <br>";
    }
    public function getType(): string
    {
        return 'courant';
    }
}
