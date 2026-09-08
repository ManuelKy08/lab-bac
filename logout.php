<?php
// Logout — LAB BAC (kikikokok)
require_once __DIR__ . '/includes/header.php';
if ($me) {
    unset($_SESSION['login']);
    session_destroy();
}
header('Location: index.php');
exit;