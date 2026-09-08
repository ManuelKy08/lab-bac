<?php
// ============================================================
// LAB BAC - Koneksi Database (Tugas Kuliah)
// Nama: kikikokok
// ============================================================

declare(strict_types=1);

$dsn = 'mysql:host=127.0.0.1;dbname=lab_bac;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

function e(mixed $v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function cek_login(): ?array
{
    return $_SESSION['login'] ?? null;
}