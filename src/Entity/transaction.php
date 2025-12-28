<?php 

class Transaction 
{
    private ?int $id;
    private int $compteId;
    private string $type;
    private float $amount;
    private DateTimeImmutable $creatAt;

    public function __construct(int $compteId, string $type, float $amount)
    {
        if($amount <= 0){
            throw new InvalidArgumentException("Montant invalide");
        }
        $this -> compteId = $compteId;
        $this -> type = $type;
        $this -> amount = $amount;
        $this -> creatAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this -> id;
    }
    /// nehtajoha f repository mn ba3d mansayviw f db 
    public function setId($id): void
    {
        $this -> id = $id;
    }

    public function getCompteId(): int
    {
        return $this -> compteId;
    }

    public function getType(): string
    {
        return $this -> type;
    }

    public function getAmount(): float
    {
        return $this -> amount;
    }
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this -> creatAt;
    }
}