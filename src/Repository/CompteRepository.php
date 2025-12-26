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
        $client = $this->clientRepo->findById($clientId);
        if (!$client) {
            throw new Exception("Il n'existe pas de client avec cet id");
        }

        if ($accountType !== 'courant' && $accountType !== 'epargne') {
            throw new InvalidArgumentException(
                "Type de compte invalide, entrez 'courant' ou 'epargne'"
            );
        }
        if ($accountType == 'courant' && $this->clientHasCourant($clientId)) {
            throw new Exception("Ce client possède déjà un compte courant");
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO compte (solde, type, client_id) VALUES (:solde, :type, :client_id)"
        );
        $stmt->execute([
            'solde'     => 0,
            'type'      => $accountType,
            'client_id' => $clientId
        ]);

        $accountId = (int) $this->pdo->lastInsertId();

        if ($accountType === 'courant') {
            return new CompteCourant($accountId, 0, $clientId);
        } else {
            return new CompteEpargne($accountId, 0, $clientId);
        }
    }

    public function updateSolde(Compte $compte)
    {
        $stmt = $this->pdo->prepare("UPDATE compte SET solde = :solde WHERE id = :id");
        $stmt->execute([
            'solde' => $compte->getSolde(),
            'id' => $compte->getId()
        ]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM compte");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $accounts = [];
        foreach ($rows as $row) {
            if ($row['type'] === 'courant') {
                $accounts[] = new CompteCourant((int)$row['id'], (float)$row['solde'], (int)$row['client_id']);
            } else {
                $accounts[] = new CompteEpargne((int)$row['id'], (float)$row['solde'], (int)$row['client_id']);
            }
        }
        return $accounts;
    }

    public function findByClientId(int $clientId): array
    {
        $client = $this -> clientRepo -> findById($clientId);
        if(!$client){
            throw new InvalidArgumentException("Il n'existe pas de client avec cet identifiant");
        }

        $stmt = $this -> pdo -> prepare("SELECT * FROM compte WHERE client_id = ?");
        $stmt -> execute ([$clientId]);
        $rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $clientAccounts = [];
        foreach($rows as $row){
            if($row['type'] === 'courant'){
                $clientAccounts [] = new CompteCourant((int)$row['id'], (float)$row['solde'], (int)$row['client_id']);
            } else{
                $clientAccounts [] = new CompteEpargne((int)$row['id'], (float)$row['solde'], (int)$row['client_id']);
            }
        }
        return $clientAccounts;
    }
}
