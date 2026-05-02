<?php
session_start();
require_once dirname(__DIR__) . '/config/helpers.php';

$action = $_GET['action'] ?? '';
if ($action !== 'update') {
    header('Location: /desainIn/index.php');
    exit;
}

require_login();
$username = trim($_POST['username'] ?? '');
$phone = trim($_POST['phone'] ?? '');
if ($username === '' || $phone === '') {
    flash('auth_error', 'Username dan nomor handphone wajib diisi.');
    header('Location: /desainIn/pages/profile.php');
    exit;
}

$update = $conn->prepare('UPDATE users SET username = :username, phone = :phone WHERE id = :id');
$update->execute([
    'username' => $username,
    'phone' => $phone,
    'id' => $_SESSION['user']['id'],
]);

$_SESSION['user']['username'] = $username;
flash('auth_success', 'Profil berhasil diperbarui.');
header('Location: /desainIn/pages/profile.php');
exit;
