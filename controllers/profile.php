<?php
session_start();
require_once dirname(__DIR__) . '/config/helpers.php';

$action = $_GET['action'] ?? '';
if ($action !== 'update') {
    header('Location: /desainIn/index.php');
    exit;
}

require_login();
$fullName = trim($_POST['full_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$bio = trim($_POST['bio'] ?? '');

if ($fullName === '' || $username === '' || $email === '' || $phone === '') {
    flash('auth_error', 'Nama lengkap, username, email, dan nomor handphone wajib diisi.');
    header('Location: /desainIn/pages/profile.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('auth_error', 'Format email tidak valid.');
    header('Location: /desainIn/pages/profile.php');
    exit;
}

$duplicate = $conn->prepare('SELECT id FROM users WHERE (username = :username OR email = :email) AND id <> :id LIMIT 1');
$duplicate->execute([
    'username' => $username,
    'email' => $email,
    'id' => $_SESSION['user']['id'],
]);

if ($duplicate->fetch(PDO::FETCH_ASSOC)) {
    flash('auth_error', 'Username atau email sudah digunakan akun lain.');
    header('Location: /desainIn/pages/profile.php');
    exit;
}

$update = $conn->prepare('UPDATE users SET full_name = :full_name, username = :username, email = :email, phone = :phone, address = :address, bio = :bio WHERE id = :id');
$update->execute([
    'full_name' => $fullName,
    'username' => $username,
    'email' => $email,
    'phone' => $phone,
    'address' => $address !== '' ? $address : null,
    'bio' => $bio !== '' ? $bio : null,
    'id' => $_SESSION['user']['id'],
]);

$_SESSION['user']['full_name'] = $fullName;
$_SESSION['user']['username'] = $username;
$_SESSION['user']['email'] = $email;
$_SESSION['user']['phone'] = $phone;
$_SESSION['user']['address'] = $address;
$_SESSION['user']['bio'] = $bio;
flash('auth_success', 'Profil berhasil diperbarui.');
header('Location: /desainIn/pages/profile.php');
exit;
