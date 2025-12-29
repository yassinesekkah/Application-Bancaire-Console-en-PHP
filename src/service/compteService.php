<?php 

class CompteService 
{
    private PDO $pdo;
    private CompteRepository $compteRepo;
    private TransactionRepository $transactionRepo;

    public function __construct(PDO $pdo, CompteRepository $compteRepo, TransactionRepository $transactionRepo)
    {
        $this -> pdo = $pdo;
        $this -> compteRepo = $compteRepo;
        $this -> transactionRepo = $transactionRepo;
    }

    public function deposer(int $compteId, float $amount): void
    {
        try{ 
             ///kanebdaw biha transaction 
            $this -> pdo -> beginTransaction();

            ///njibo l compte mn db
            $compte = $this -> compteRepo -> findById($compteId);

            if(!$compte){
                throw new Exception("Compte Introuvable");
            }
            /// dir depot
            $compte->deposit($amount);
            ///updat dyal solde
            $this->compteRepo->updateSolde($compte);

            /// save l transaction
            $transaction = new Transaction(
                $compte->getId(),
                'deposit',
                $amount
            );

            $this->transactionRepo->save($transaction);
            $this->pdo->commit();

        }catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function retirer(int $compteId, float $amount): void
    {
        try {
            $this->pdo->beginTransaction();

            $compte = $this->compteRepo->findById($compteId);
            if (!$compte) {
                throw new Exception("Compte introuvable");
            }

            // Logique métier
            $compte->withdraw($amount);

            // Mise à jour du solde
            $this->compteRepo->updateSolde($compte);

            // Enregistrement de la transaction
            $transaction = new Transaction(
                $compte->getId(),
                'withdraw',
                $amount
            );
            $this->transactionRepo->save($transaction);

            $this->pdo->commit();

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function historique(int $compteId): array
    {
        $compte = $this->compteRepo->findById($compteId);
        if (!$compte) {
            throw new Exception("Compte introuvable");
        }

        return $this->transactionRepo->findByCompteId($compteId);
    }
}
