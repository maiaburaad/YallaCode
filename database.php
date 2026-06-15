<?php

function getDatabaseConnection(): PDO
{
    $configFile = __DIR__ . '/config.php';

    if (!file_exists($configFile)) {
        throw new RuntimeException(
            'Missing config.php. Copy config.example.php to config.php and update the database settings.'
        );
    }

    $config = require $configFile;
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['database']
    );

    return new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
