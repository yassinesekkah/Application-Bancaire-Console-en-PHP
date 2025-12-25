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

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM clients");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clients = [];
        foreach ($rows as $row) {
            $clients[] = new Client($row['nom'], $row['email']);
        }
        return $clients;
    }

    public function findById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Client($row['nom'], $row['email']);
        } else {
            return null;
        }
    }

    public function updateClient(int $id, string $newNom, string $newEmail)
    {
        //check
        $client = $this->findById($id);
        if (!$client) {
            throw new Exception("Il n'existe pas de client avec cet id");
        }

        if (strlen($newNom) < 2) {
            throw new InvalidArgumentException("le nom est tres court");
        }
        if($client -> getEmail() !== $newEmail){
            $stmt = $this -> pdo -> prepare("SELECT count(*) FROM clients WHERE email = ? AND id != ?");
            $stmt -> execute([$newEmail, $id]);
            $count = $stmt -> fetchColumn();

            if($count > 0 ){
                throw new InvalidArgumentException("Cet email est déjà utilisé");
            }
        }
        //update
        $stmt = $this -> pdo -> prepare("UPDATE clients SET nom = ?, email = ? WHERE id = ?");
        $stmt -> execute([$newNom, $newEmail, $id]);
    }

    public function deleteClient (int $id){

        $client = $this -> findById($id);

        if(!$client){
            throw new InvalidArgumentException("Il n'existe pas de client avec cet id");
        }

        $stmt = $this -> pdo -> prepare("SELECT count(*) FROM compte WHERE client_id = ?");
        $stmt -> execute([$id]);
        $count = $stmt -> fetchColumn();

        if($count > 0){
            throw new InvalidArgumentException("Impossible de supprimer ce client, 
                                    il possède déjà un compte bancaire");
        }

        //delete
        $stmt = $this -> pdo -> prepare("DELETE FROM clients WHERE id = ?");
        $stmt -> execute([$id]);
        
    }
}
