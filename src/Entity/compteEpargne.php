<?php

class CompteEpargne extends Compte
{
    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif ");
        }
        $this -> solde += $amount;
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif ");
        }
        if ($this -> solde < $amount){
            throw new RuntimeException("Solde insuffisant");
        }
        $this -> solde -= $amount;
    }

    public function getType(): string
    {
        return 'epargne';
    }
}
