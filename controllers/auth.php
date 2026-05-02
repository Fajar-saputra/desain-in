<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

$action = $_GET['action'] ?? '';
$redirect = '/desainIn/index.php';

function flash($key, $message) {
    $_SESSION[$key] = $message;
}

if ($action === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        flash('auth_error', 'Username dan password wajib diisi.');
        $redirect = '/desainIn/index.php';
    } else {
        $stmt = $conn->prepare('SELECT id, username, phone, password_hash, role FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            flash('auth_error', 'Username atau password tidak cocok.');
            $redirect = '/desainIn/index.php';
        } else {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'phone' => $user['phone'],
                'role' => $user['role'],
            ];
            header('Location: /desainIn/pages/service.php');
            exit;
        }
    }
    $_SESSION['auth_open'] = true;
    $_SESSION['auth_tab'] = 'login';
    header('Location: ' . $redirect);
    exit;
}

if ($action === 'register') {
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username === '' || $phone === '' || $password === '' || $confirm === '') {
        flash('auth_error', 'Semua field harus diisi.');
        $redirect = '/desainIn/index.php';
    } elseif ($password !== $confirm) {
        flash('auth_error', 'Password dan konfirmasi password tidak cocok.');
        $redirect = '/desainIn/index.php';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            flash('auth_error', 'Username sudah digunakan. Silakan pilih username lain.');
            $redirect = '/desainIn/index.php';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare('INSERT INTO users (username, phone, password_hash, role) VALUES (:username, :phone, :password_hash, :role)');
            $insert->execute([
                'username' => $username,
                'phone' => $phone,
                'password_hash' => $hash,
                'role' => 'user',
            ]);

            flash('auth_success', 'Registrasi berhasil. Silakan login.');
            $redirect = '/desainIn/index.php';
            $_SESSION['auth_tab'] = 'login';
        }
    }

    $_SESSION['auth_open'] = true;
    header('Location: ' . $redirect);
    exit;
}

header('Location: ' . $redirect);
exit;
