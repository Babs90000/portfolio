<?php

if (getenv('JAWSDB_URL') !== false) {
    $url = getenv('JAWSDB_URL');
    $dbparts = parse_url($url);
    $hostname = $dbparts['host'];
    $username = $dbparts['user'];
    $password = $dbparts['pass'];
    $database = ltrim($dbparts['path'], '/');
} else {
    $hostname = 'localhost'; 
    $username = 'root';
    $password = '';
    $database = 'portefolio_db';
}

try {
    $bdd = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit();
}