<?php 

abstract class Compte 
{
    private ?int $id;
    private float $solde;
    private int $clientId;

    public function __construct(float $solde, int $clientId)
    {
        $this -> id = null;
        $this -> solde = $solde;
        $this -> clientId = $clientId;
    }

    public function getID(): ?int
    {
        return $this -> id;
    }

    public function getSolde(): float
    {
        return $this -> solde;
    }

    public function getClientId(): int
    {
         return $this -> clientId;
    }
    public function setSolde($amount): float
    {
        return $this -> solde = $amount;
    }



    //abstract methods
    abstract public function deposit (float $amount): void;
    abstract public function withdraw (float $amount): void;
}