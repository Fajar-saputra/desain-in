<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

function require_login() {
    if (empty($_SESSION['user'])) {
        header('Location: /desainIn/index.php');
        exit;
    }
}

function require_role(array $roles) {
    require_login();
    if (!in_array($_SESSION['user']['role'], $roles, true)) {
        header('Location: /desainIn/index.php');
        exit;
    }
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function flash($key, $message) {
    $_SESSION[$key] = $message;
}

function get_flash($key) {
    $value = $_SESSION[$key] ?? null;
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
    return $value;
}
