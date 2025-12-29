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

    public function saveAccount(Compte $compte): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO compte (solde, type, client_id) VALUES (:solde, :type, :client_id)"
        );
        $stmt->execute([
            'solde'     => $compte->getSolde(),
            'type'      => $compte->getType(),
            'client_id' => $compte->getClientId()
        ]);

        $accountId = (int) $this->pdo->lastInsertId();

        $compte->setID($accountId);
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

    public function findById(int $id): ?Compte
    {
        $stmt = $this->pdo->prepare("SELECT * FROM compte WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        if ($row['type'] === 'courant') {
            $account = new CompteCourant((int) $row['client_id']);
        } else {
            $account = new CompteEpargne((int) $row['client_id']);
        }

        $account->setId((int) $row['id']);
        $account->setSolde((float) $row['solde']);

        return $account;
    }


    public function findByClientId(int $clientId): array
    {
        $client = $this->clientRepo->findById($clientId);
        if (!$client) {
            throw new InvalidArgumentException("Il n'existe pas de client avec cet identifiant");
        }

        $stmt = $this->pdo->prepare("SELECT * FROM compte WHERE client_id = ?");
        $stmt->execute([$clientId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clientAccounts = [];
        foreach ($rows as $row) {
            if ($row['type'] === 'courant') {
                $clientAccounts[] = new CompteCourant((int)$row['id'], (float)$row['solde'], (int)$row['client_id']);
            } else {
                $clientAccounts[] = new CompteEpargne((int)$row['id'], (float)$row['solde'], (int)$row['client_id']);
            }
        }
        return $clientAccounts;
    }

    public function deleteCompte(int $accountId): void
    {
        $stmt = $this->pdo->prepare("SELECT solde FROM compte WHERE id = :id");
        $stmt->execute(["id" => $accountId]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$account) {
            throw new InvalidArgumentException("Ce compte n'existe pas");
        }

        if ((float)$account['solde'] !== 0.0) {
            throw new InvalidArgumentException("Ce compte possède un solde. Il ne peut pas être supprimé");
        }
        //delete daba wila banet chi if nzido mn ba3d
        $stmt = $this->pdo->prepare("DELETE FROM compte WHERE id = :id");
        $stmt->execute([
            "id" => $accountId
        ]);
    }
}
