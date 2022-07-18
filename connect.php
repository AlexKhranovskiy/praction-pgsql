<?php

try {
    $pdo = new PDO(
        'pgsql:host=localhost;port=5432;dbname=postgres',
        'postgres',
        'alexalex',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    echo 'Unable connect to the DB';
}
