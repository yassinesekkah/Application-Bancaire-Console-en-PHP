<?php 

abstract class Compte 
{
    private ?int $id = null;
    protected float $solde = 0;
    private int $clientId;

    public function __construct(int $clientId)
    {   
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
    
    public function setID($id): void
    {
        $this -> id = $id;
    }
    public function setSolde($amount)
    {
        $this -> solde = $amount;
    }

    //abstract methods
    abstract public function deposit (float $amount): void;
    abstract public function withdraw (float $amount): void;
    abstract public function getType(): string;
}