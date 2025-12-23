<?php 

class ClientRepository 
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this -> pdo = $pdo;

    }

    public function save(Client $client): void
    {
        $stmt = $this -> pdo -> prepare("INSERT into clients (nom, email) VALUES (:nom, :email)");

        $nom = $client -> getNom();
        $email = $client -> getEmail();

        $stmt -> execute([
            ':nom' => $nom,
            ':email' => $email
        ]);
    }
    
}