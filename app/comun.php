<?php

session_start();

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function campo(string $nombre): string
{
    $valor = $_POST[$nombre] ?? '';
    return is_string($valor) ? $valor : '';
}

function irA(string $ruta): never
{
    header('Location: ' . $ruta, true, 303);
    exit;
}

function tokenFormulario(): string
{
    $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function comprobarFormulario(): void
{
    $token = $_SESSION['csrf'] ?? '';
    if ($token === '' || !hash_equals($token, campo('csrf'))) {
        http_response_code(403);
        exit('El formulario caducó. Vuelve a abrir la página.');
    }
}
