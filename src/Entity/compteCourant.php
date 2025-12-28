<?php

class CompteCourant extends Compte
{
    private const fees = 5;
    private const decouverMax = -500;

    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif ");
        }
        $this -> solde += ($amount - self::fees);
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif ");
        }
        if ($this -> solde - $amount < self::decouverMax) {
            throw new InvalidArgumentException("Découvert dépassé");
        }
        $this -> solde -= $amount;
    }

    public function getType(): string
    {
        return 'courant';
    }
}
