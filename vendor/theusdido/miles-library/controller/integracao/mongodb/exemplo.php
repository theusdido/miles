<?php
    use MongoDB\Driver\ServerApi;
    $uri = 'mongodb+srv://innovare:nqgE1zSpywrkPb9C@innovare.2izl3nj.mongodb.net/?retryWrites=true&w=majority&appName=innovare';
    // Set the version of the Stable API on the client
    $apiVersion = new ServerApi(ServerApi::V1);
    // Create a new client and connect to the server
    $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
    try {
        // Send a ping to confirm a successful connection
        $client->selectDatabase('admin')->command(['ping' => 1]);
        echo "Pinged your deployment. You successfully connected to MongoDB!\n";
    } catch (Exception $e) {
        printf($e->getMessage());
    }