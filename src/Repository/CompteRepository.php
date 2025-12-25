<?php

class CompteRepository
{
    private PDO $pdo;
    private ClientRepository $clientRepo;

    public function __construct(PDO $pdo, ClientRepository $clientRepo)
    {
        $this->pdo = $pdo;
        $this->clientRepo = $clientRepo;
    }

    public function createAccount(int $clientId,string $accountType)
    {
        $client = $this->clientRepo->findById($clientId);

        if (!$client) {
            throw new Exception("Il n'existe pas de client avec cet id");
        }

        if ($accountType !== 'courant' && $accountType !== 'epargne') {
            throw new InvalidArgumentException("Invalid type de compte, 
                                                    s'il vous plait entrait 'courant' ou 'epargne'");
        }

        $stmt = $this->pdo->prepare
                        ("INSERT INTO compte (solde, type, client_id) VALUES (0, :type, :client_id)");
        $stmt->execute([
            'type' => $accountType,
            'client_id' => $clientId
        ]);

        //return 3la hsab type dyal compte 
        if($accountType === 'courant'){
            return new CompteCourant(0, $clientId);
        }
        else{
            return new CompteEpargne(0, $clientId);
        }
    }
}
