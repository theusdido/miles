<?php

    require 'vendor/kreait/firebase-php/src/Firebase/Factory.php';
    require 'vendor/kreait/firebase-php/src/Firebase/Database.php';

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Database;

    // Carrega as credenciais do Firebase
    $serviceAccount = 'innovareadministradora';

    // Cria uma instância do Firebase
    $firebase = (new Factory) 
    ->withServiceAccount('firebase.json')
    ->withDatabaseUri('https://innovareadministradora-default-rtdb.firebaseio.com/');

    // Obtém uma referência ao banco de dados
    $database = $firebase->createDatabase();

    // Dados a serem inseridos
    $userData = [
        'name' => 'João da Silva',
        'email' => 'joao@example.com'
    ];

    // Insere os dados na referência 'users'
    $database->getReference('users')->push($userData);

    echo 'Usuário inserido com sucesso!';