<?php

class ClientRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function isEmailUnique(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT Count(*) FROM clients WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $count = $stmt->fetchColumn();

        return $count == 0;
    }

    public function save(Client $client): void
    {
        $stmt = $this->pdo->prepare("INSERT into clients (nom, email) VALUES (:nom, :email)");

        if (!$this->isEmailUnique($client->getEmail())) {
            throw new Exception("Email déjà utilisé");
        }
        $nom = $client->getNom();
        $email = $client->getEmail();

        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email
        ]);
    }
}
