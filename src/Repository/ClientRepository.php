<?php

class ClientRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this -> pdo = $pdo;
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
        $stmt = $this -> pdo -> query("SELECT * FROM clients");
        $rows = $stmt ->fetchAll(PDO::FETCH_ASSOC);

        $clients = [];
        $lenght = count($rows);
        
        for($i = 0; $i < $lenght; $i++){
            $clients [] = new Client(
                $rows[$i]['nom'],
                $rows[$i]['email']
            );
        }
        return $clients;
    }

    public function findById (int $id){
        $stmt = $this -> pdo -> prepare ("SELECT * FROM clients WHERE id = ?");
        $stmt -> execute([$id]);
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);

        if($row){
            return new Client($row['nom'], $row['email']);
        }else{
            return null;
        }
    }

}





