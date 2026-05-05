<?php
require_once __DIR__ . '/../config/helpers.php';
$redirect = '/desainIn/index.php';

$action = $_GET['action'] ?? '';

if ($action === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        flash('auth_error', 'Username dan password wajib diisi.');
    } else {
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'role' => $user['role']
            ];

            // redirect role
            switch ($user['role']) {
                case 'admin':
                    header("Location: /desainIn/admin/admin_dashboard.php");
                    break;
                case 'designer':
                    header("Location: /desainIn/designer/designer.php");
                    break;
                default:
                    header("Location: /desainIn/index.php");
            }
            exit;
        } else {
            flash('auth_error', 'Username atau password salah.');
        }
    }

    $_SESSION['auth_open'] = true;
    $_SESSION['auth_tab'] = 'login';
    header('Location: /desainIn/index.php');
    exit;
}

if ($action === 'register') {
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($fullName === '' || $username === '' || $email === '' || $phone === '' || $password === '' || $confirm === '') {
        flash('auth_error', 'Semua field harus diisi.');
        $redirect = '/desainIn/index.php';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('auth_error', 'Format email tidak valid.');
        $redirect = '/desainIn/index.php';
    } elseif ($password !== $confirm) {
        flash('auth_error', 'Password dan konfirmasi password tidak cocok.');
        $redirect = '/desainIn/index.php';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1');
        $stmt->execute([
            'username' => $username,
            'email' => $email,
        ]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            flash('auth_error', 'Username atau email sudah digunakan.');
            $redirect = '/desainIn/index.php';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare('INSERT INTO users (username, full_name, email, phone, password_hash, role) VALUES (:username, :full_name, :email, :phone, :password_hash, :role)');
            $insert->execute([
                'username' => $username,
                'full_name' => $fullName,
                'email' => $email,
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
