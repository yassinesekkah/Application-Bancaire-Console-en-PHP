<?php 

class CompteFactory
{
    static public function CreatCompte (int $clientId, string $accountType): Compte
    {
        if($accountType === "courant"){
            $compte = new CompteCourant($clientId);
        }
        elseif($accountType === "epargne"){
            $compte = new CompteEpargne($clientId);
        }
        else{
            throw new InvalidArgumentException("Type de compte invalide. Types autorisés : 'courant', 'epargne'.");
        }
        return $compte;
    }
}