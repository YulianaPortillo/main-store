<?php

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '5432';
$database = getenv('DB_NAME') ?: 'combox';
$user = getenv('DB_USER') ?: 'postgres';
$password = getenv('DB_PASSWORD') ?: '';

$conn = pg_connect(
    "host=$host port=$port dbname=$database user=$user password=$password"
);

if (!$conn) {
    echo "No se pudo conectar a la base de datos.";
}
