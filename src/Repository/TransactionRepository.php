<?php 

class TransactionRepository 
{
    private PDO $pdo;
   

    public function __construct(PDO $pdo)
    {
        $this -> pdo = $pdo;
    }

    public function save(Transaction $transaction): void
    {
        $stmt = $this -> pdo -> prepare
                                ("INSERT INTO transaction (TYPE, montant, DATE, compte_id) 
                                VALUES (:type, :montant, :date, :compte_id)");
        $stmt -> execute([
            'type'      => $transaction -> getType(),
            'montant'   => $transaction -> getAmount(),
            'date'      => $transaction -> getCreatedAt()->format('Y-m-d H:i:s'),,
            'compte_id' => $transaction -> getCompteId()
        ]);

        $transactionId = (int) $this -> pdo -> lastInsertId();
        $transaction -> setId($transactionId);
    }
}