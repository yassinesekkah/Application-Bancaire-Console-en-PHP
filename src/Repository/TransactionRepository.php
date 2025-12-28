<?php

class TransactionRepository
{
    private PDO $pdo;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Transaction $transaction): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO transaction (TYPE, montant, DATE, compte_id) 
                                VALUES (:type, :montant, :date, :compte_id)");
        $stmt->execute([
            'type'      => $transaction->getType(),
            'montant'   => $transaction->getAmount(),
            'date'      => $transaction->getCreatedAt()->format('Y-m-d H:i:s'),,
            'compte_id' => $transaction->getCompteId()
        ]);

        $transactionId = (int) $this->pdo->lastInsertId();
        $transaction->setId($transactionId);
    }

    public function findByCompteId($compteId)
    {
        $stmt = $this->pdo->prepare("SELECT id, TYPE, montant, date, compte_id FROM transaction WHERE compte_id = :compte_id");
        $stmt->execute([
            'compte_id' => $compteId
        ]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $transactions = [];

        foreach ($rows as $row) {
            $transaction = new Transaction(
                (int) $row['compte_id'], 
                (string) $row['type'], 
                (float) $row['montant'], 
                new DateTimeImmutable( $row['date'])
            );
            $transaction -> setId((int) $row['id']);
            $transactions [] = $transaction;
        }
        return $transactions;
    }
}
