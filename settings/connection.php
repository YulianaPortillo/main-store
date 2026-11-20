<?php

$conn = pg_connect("host=localhost port=5432 dbname=combox user=postgres password=abc12345");

if (!$conn) {
    echo "No se pudo conectar a la base de datos.";
}
