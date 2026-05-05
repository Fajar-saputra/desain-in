<?php 
require_once __DIR__ . '/../config/helpers.php';

$action = $_GET['action'] ?? '';
if ($action !== 'update') {
    header('Location: /desainIn/index.php');
    exit;
}

require_login();

function save_profile_picture() {
    if (empty($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        return null;
    }

    $uploadDir = dirname(__DIR__) . '/assets/uploads/profiles';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = 'profile_' . $_SESSION['user']['id'] . '_' . time() . '.' . $extension;
    $targetPath = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
        return null;
    }

    return '/desainIn/assets/uploads/profiles/' . $fileName;
}

$fullName = trim($_POST['full_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$bio = trim($_POST['bio'] ?? '');
$password = $_POST['password'] ?? '';

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

$profilePicture = save_profile_picture();
$fields = [
    'full_name = :full_name',
    'username = :username',
    'email = :email',
    'phone = :phone',
    'address = :address',
    'bio = :bio',
];
$params = [
    'full_name' => $fullName,
    'username' => $username,
    'email' => $email,
    'phone' => $phone,
    'address' => $address !== '' ? $address : null,
    'bio' => $bio !== '' ? $bio : null,
    'id' => $_SESSION['user']['id'],
];

if ($password !== '') {
    $fields[] = 'password_hash = :password_hash';
    $params['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
}

if ($profilePicture !== null) {
    $fields[] = 'profile_picture = :profile_picture';
    $params['profile_picture'] = $profilePicture;
}

$update = $conn->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id');
$update->execute($params);

$_SESSION['user']['full_name'] = $fullName;
$_SESSION['user']['username'] = $username;
$_SESSION['user']['email'] = $email;
$_SESSION['user']['phone'] = $phone;
$_SESSION['user']['address'] = $address;
$_SESSION['user']['bio'] = $bio;
if ($profilePicture !== null) {
    $_SESSION['user']['profile_picture'] = $profilePicture;
}
flash('auth_success', 'Profil berhasil diperbarui.');
header('Location: /desainIn/pages/profile.php');
exit;
