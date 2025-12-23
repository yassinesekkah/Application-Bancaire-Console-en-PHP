<?php 
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../src/Entity/client.php';
    require_once __DIR__ . '/../src/Repository/ClientRepository.php';

    //class dyal repository 3tinaha pdo lijabnah mn conexion m3a DB
    $repository = new ClientRepository($pdo);

    $client = new Client("Ali", "ali@email.com");

     $client = new Client("Mostapha", "mustafa@email.com");


    $repository -> save($client);

    echo "Client ajoute avec succes". PHP_EOL;