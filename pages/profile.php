<?php
require_once __DIR__ . '/../config/helpers.php';
require_login();

$stmt = $conn->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $_SESSION['user']['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: /desainIn/logout.php');
    exit;
}

$roleLabel = $user['role'] === 'admin' ? 'Administrator' : ($user['role'] === 'designer' ? 'Designer' : 'Pelanggan');
$profilePicture = $user['profile_picture'] ?? '';

if ($profilePicture && strpos($profilePicture, '/desainIn/') === 0) {
    $avatarUrl = $profilePicture;
} elseif ($profilePicture && file_exists(__DIR__ . '/../assets/images/' . $profilePicture)) {
    $avatarUrl = '/desainIn/assets/images/' . $profilePicture;
} else {
    $avatarUrl = '/desainIn/assets/images/grav-profile.png';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil - DesainIn</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/desainIn/assets/css/styles.css">
</head>
<body class="profile-body">
  <header class="profile-topbar">
    <div class="profile-user-title">
      <span class="profile-user-icon" aria-hidden="true"></span>
      <strong>User: <?= htmlspecialchars($user['username']); ?></strong>
    </div>
    <button class="profile-save-button" form="profileForm" type="submit">
      <span aria-hidden="true">&check;</span>
      Save
    </button>
  </header>

  <main class="profile-page">
    <?php if ($message = get_flash('auth_error')): ?>
      <div class="alert alert-danger profile-alert"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($message = get_flash('auth_success')): ?>
      <div class="alert alert-success profile-alert"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form id="profileForm" class="profile-panel" action="/desainIn/controllers/profile.php?action=update" method="POST" enctype="multipart/form-data">
      <section class="profile-hero">
        <div class="profile-avatar-wrap">
          <img src="<?= htmlspecialchars($avatarUrl); ?>" alt="Foto profil <?= htmlspecialchars($user['full_name'] ?: $user['username']); ?>" class="profile-avatar">
        </div>

        <div class="profile-summary">
          <h1><?= htmlspecialchars($user['full_name'] ?: $user['username']); ?></h1>
          <p>
            <a href="mailto:<?= htmlspecialchars($user['email']); ?>"><?= htmlspecialchars($user['email']); ?></a>
            <span>- <?= htmlspecialchars($roleLabel); ?></span>
          </p>
          <small>Avatar by gravatar.com. Or upload your own...</small>

          <label class="profile-upload-zone">
            <input type="file" name="profile_picture" accept=".jpg,.jpeg,.png,.webp">
            <span>Drop your files here or <strong>click in this area</strong></span>
          </label>
        </div>
      </section>

      <section class="profile-form-section">
        <h2>Account</h2>

        <div class="profile-field-grid">
          <label for="username">Username</label>
          <input id="username" type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

          <label for="email">Email <span>*</span></label>
          <input id="email" type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>

          <label for="password">Password</label>
          <input id="password" type="password" name="password" placeholder="Kosongkan jika tidak diubah">

          <label for="full_name">Full name <span>*</span></label>
          <input id="full_name" type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? ''); ?>" required>

          <label for="phone">Phone <span>*</span></label>
          <input id="phone" type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? ''); ?>" required>

          <label for="role">Role</label>
          <input id="role" type="text" value="<?= htmlspecialchars($user['role']); ?>" disabled>

          <label for="address">Address</label>
          <textarea id="address" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? ''); ?></textarea>

          <label for="bio">Bio</label>
          <textarea id="bio" name="bio" rows="3"><?= htmlspecialchars($user['bio'] ?? ''); ?></textarea>

          <label for="created_at">Created at</label>
          <input id="created_at" type="text" value="<?= htmlspecialchars(date('d M Y H:i', strtotime($user['created_at']))); ?>" disabled>
        </div>
      </section>
    </form>
  </main>
</body>
</html>
