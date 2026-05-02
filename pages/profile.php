<?php
require_once __DIR__ . '/../config/helpers.php';
require_login();
$user = $_SESSION['user'];

$roleLabel = $user['role'] === 'admin' ? 'Admin' : ($user['role'] === 'designer' ? 'Designer' : 'Pelanggan');
?>
<?php include __DIR__ . '/../components/navbar.php'; ?>
<div class="container my-5">
  <div class="row">
    <div class="col-lg-8 mx-auto">
      <div class="card shadow-sm border-0 p-4">
        <h1 class="fw-bold mb-3">Profil <?= htmlspecialchars($roleLabel); ?></h1>
        <?php if ($message = get_flash('auth_error')): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($message = get_flash('auth_success')): ?>
          <div class="alert alert-success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="/desainIn/controllers/profile.php?action=update" method="POST">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Handphone</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? ''); ?>" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Peran</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($roleLabel); ?>" disabled>
          </div>
          <button class="btn btn-order w-100" type="submit">Simpan Profil</button>
        </form>
      </div>
    </div>
  </div>
</div>
