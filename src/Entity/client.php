<?php
require_once './config/database.php';

class Client 
{
    private ?int $id;
    protected string $nom;
    protected string $email;

    public function __construct(string $nom, string $email)
    {
        $this ->setNom($nom);
        $this ->setEmail($email);
    }

    public function getId (): ?int
    {
        return $this -> id;
    }

    public function getNom(): string
    {
        return $this -> nom;
    }

    public function getEmail(): string
    {
        return $this -> email;
    }

    public function setNom($nom): void
    { 
        if(strlen($nom) < 2){
            throw new InvalidArgumentException("Nom trop court");
        }
        $this -> nom = $nom;
    }

    public function setEmail($email): void
    {

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new InvalidArgumentException("Email invalid");
        }
        $this -> email = $email;
    }
}

