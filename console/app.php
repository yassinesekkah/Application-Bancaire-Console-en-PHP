<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Entity/client.php';
require_once __DIR__ . '/../src/Repository/ClientRepository.php';

//class dyal repository 3tinaha pdo lijabnah mn conexion m3a DB
$clientRepository = new ClientRepository($pdo);

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
foreach($clientsToAdd as $client){
    try{
        $clientRepository -> save($client);
        echo "Client ajoute";
        echo "\n";
    }catch(exception $e){
        echo "Erreur : " . $e -> getMessage() . "<br>";
    }
}
//Affichage des clients
echo  "<br> === LISTE DES CLIENTS === <br>";
$allClients = $clientRepository -> findAll();

foreach($allClients as $client){
    echo $client -> getNom() . " | " . $client -> getEmail() . "<br>";
}
//Affichage de client avec le id 
echo "<br>=== FIND BY ID ===<br>";

$resFindById = $clientRepository->findById(2);

if (!$resFindById) {
    echo "There is no client with this id <br>";
} else {
    echo $resFindById->getNom() . " | " . $resFindById->getEmail() . "<br>";
}




