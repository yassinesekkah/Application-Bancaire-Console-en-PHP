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

    public function clientHasCourant(int $clientId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT count(*) FROM compte WHERE client_id = :client AND type = 'courant'"
        );
        $stmt->execute(['client' => $clientId]);

        return $stmt->fetchColumn() > 0;
    }

    public function createAccount(int $clientId, string $accountType): Compte
    {
        // التأكد أن العميل موجود
        $client = $this->clientRepo->findById($clientId);
        if (!$client) {
            throw new Exception("Il n'existe pas de client avec cet id");
        }

        // التأكد من نوع الحساب
        if ($accountType !== 'courant' && $accountType !== 'epargne') {
            throw new InvalidArgumentException(
                "Type de compte invalide, entrez 'courant' ou 'epargne'"
            );
        }
        if($accountType == 'courant' && $this -> clientHasCourant($clientId)){
           throw new Exception("Ce client possède déjà un compte courant");
        }

        // إدخال الحساب في DB
        $stmt = $this->pdo->prepare(
            "INSERT INTO compte (solde, type, client_id) VALUES (:solde, :type, :client_id)"
        );
        $stmt->execute([
            'solde'     => 0,
            'type'      => $accountType,
            'client_id' => $clientId
        ]);

        // id الجديد من DB
        $accountId = (int) $this->pdo->lastInsertId();

        // إنشاء object المناسب مع id و return
        if ($accountType === 'courant') {
            return new CompteCourant($accountId, 0, $clientId);
        } else {
            return new CompteEpargne($accountId, 0, $clientId);
        }
    }

    // public function createAccount(int $clientId,string $accountType): Compte
    // {
    //     $client = $this->clientRepo->findById($clientId);

    //     if (!$client) {
    //         throw new Exception("Il n'existe pas de client avec cet id");
    //     }

    //     if ($accountType !== 'courant' && $accountType !== 'epargne') {
    //         throw new InvalidArgumentException("Invalid type de compte, 
    //                                                 s'il vous plait entrait 'courant' ou 'epargne'");
    //     }
    //     if($accountType == 'courant' && $this -> clientHasCourant($clientId)){
    //         throw new Exception("Ce client possède déjà un compte courant");
    //     }

    //     $stmt = $this->pdo->prepare
    //                     ("INSERT INTO compte (solde, type, client_id) VALUES (:solde, :type, :client_id)");
    //     $stmt->execute([
    //         'solde' => 0,
    //         'type' => $accountType,
    //         'client_id' => $clientId
    //     ]);

    //     $accountId = (int) $this -> pdo -> lastInsertId();
    //     //return 3la hsab type dyal compte 
    //     if($accountType === 'courant'){
    //         return new CompteCourant($accountId, 0, $clientId);
    //     }
    //     else{
    //         return new CompteEpargne($accountId, 0, $clientId);
    //     }
    // }

    public function updateSolde(Compte $compte)
    {
        $stmt = $this->pdo->prepare("UPDATE compte SET solde = :solde WHERE id = :id");
        $stmt->execute([
            'solde' => $compte->getSolde(),
            'id' => $compte->getId()
        ]);
    }
}
