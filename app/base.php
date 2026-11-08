<?php

function baseDeDatos(): PDO
{
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $puerto = getenv('DB_PORT') ?: '5432';
    $nombre = getenv('DB_NAME') ?: 'papeleria';
    $usuario = getenv('DB_USER') ?: 'postgres';
    $dsn = "pgsql:host=$host;port=$puerto;dbname=$nombre";

    return new PDO($dsn, $usuario, (string) getenv('DB_PASSWORD'), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
