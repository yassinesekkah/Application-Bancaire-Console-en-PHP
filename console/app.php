<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Entity/client.php';
require_once __DIR__ . '/../src/Repository/ClientRepository.php';
require_once __DIR__ . '/../src/Repository/CompteRepository.php';
require_once __DIR__ . '/../src/Entity/compte.php';
require_once __DIR__ . '/../src/Entity/CompteCourant.php';
require_once __DIR__ . '/../src/Entity/compteEpargne.php';
require_once __DIR__ . '/../src/factory/compteFactory.php';


//class dyal repository 3tinaha pdo lijabnah mn conexion m3a DB
$clientRepository = new ClientRepository($pdo);
$compteRepository = new CompteRepository($pdo, $clientRepository);

// var_dump($clientRepository -> findAll());

$clientsToAdd = [
    new Client("Mostapha", "mustafa@email.com"),
    new Client("Mostapha", "mustafa10@email.com"),
    new Client("Layla", "layla@email.com"),
    new Client("Lamine", "lamine@email.com"),
    new Client("Ahmed", "ahmed@gmail.com"),
    new Client("Amine", "amine@gmail.com")
];
//save les clients sur le Database
foreach ($clientsToAdd as $client) {
    try {
        $clientRepository->save($client);
        echo "Client ajoute";
        echo "\n";
    } catch (exception $e) {
        echo "Erreur : " . $e->getMessage() . "<br>";
    }
}
////===============================> Affichage des clients <==============================================////

echo  "<br> === LISTE DES CLIENTS === <br>";
$allClients = $clientRepository->findAll();

foreach ($allClients as $client) {
    echo $client->getNom() . " | " . $client->getEmail() . "<br>";
}
////============================> Affichage de client avec le id <=========================================////

echo "<br>=== FIND BY ID ===<br>";

$resFindById = $clientRepository->findById(2);

if (!$resFindById) {
    echo "There is no client with this id <br>";
} else {
    echo $resFindById->getNom() . " | " . $resFindById->getEmail() . "<br>";
}
////==================================> update client <================================================////

echo "<br>=== UPDATE BY ID ===<br>";
try {
    $clientRepository->updateClient(3, "Yassine", "yassine@email.com");
    echo "Client mis à jour avec succès <br>";
} catch (Exception $e) {
    echo "Erreur" . $e->getMessage() . "<br>";
}
//////delete client
echo "<br>=== DELETE BY ID ===<br>";
try {
    $clientRepository->deleteClient(7);
    echo "Client supprime avec succès <br>";
} catch (Exception $e) {
    echo "Erreur" . $e->getMessage() . "<br>";
}

////==================================> Ajouter un compte <================================================////
echo "<br>=== CREER UN COMPTE ===<br>";

$clientId = 1;       //// hna input ktab client id libghiti t cree lih l compte
$accountType = 'epargne';   /// hna input dyal account type epargne wla courant

$client = $clientRepository->findById($clientId);
if (!$client) {
    throw new Exception("Il n'existe pas de client avec cet id");
}
if ($accountType === 'courant' && $compteRepository -> clientHasCourant($clientId)) {
    throw new Exception("Ce client possède déjà un compte courant");
}
$compte = CompteFactory::creatCompte($clientId, $accountType);

$compteRepository -> saveAccount($compte);

echo "Compte créé avec succès (ID: " . $compte->getId() . ") <br>" ;

////===============================> Afficher tous les comptes <==========================================////
echo "<br> === LISTE DES COMPTE === <br>";

$allAccounts = $compteRepository->findAll();

foreach ($allAccounts as $account) {
    echo "Client ID: " . $account->getClientId() . " | ";
    echo "Type: " . ($account instanceof CompteCourant ? 'Courant' : 'Epargne') . " | ";
    echo "Solde: " . $account->getSolde() . " DH <br>";
}
////===============================> trouver Compte Par Id <==========================================////

echo "<br>=== trouver Compte Par Id ===<br>";
$clientAccounts = $compteRepository->findByClientId(6);

foreach ($clientAccounts as $account) {
    echo "Client ID: " . $account->getClientId() . " | ";
    echo "Type: " .  ($account instanceof CompteCourant ? 'Courant' : 'Epargne') . " | ";
    echo "Solde: " . $account->getSolde() . " DH <br>";
}

echo "<br>=== DELETE ACCOUNT BY ID ===<br>";
try {
    $compteRepository->deleteCompte(1);
    echo "Compte supprime avec succès <br>";
} catch (Exception $e) {
    echo "erreur " . $e->getMessage();
}
