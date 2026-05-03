<?php
require_once __DIR__ . '/../config/helpers.php';

$designerStmt = $conn->prepare('SELECT id, username, full_name, email, phone, bio FROM users WHERE role = :role ORDER BY full_name ASC');
$designerStmt->execute(['role' => 'designer']);
$designers = $designerStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include __DIR__ . '/../components/navbar.php'; ?>
<div class="container my-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold text-3xl">Our Designer</h2>
    <p class="text-muted mx-auto" style="max-width: 720px;">Temui tim designer kreatif kami yang siap membantu mewujudkan proyek visual Anda. Jika belum ada designer, tambahkan akun dengan role <strong>designer</strong> di database.</p>
  </div>

  <?php if (empty($designers)): ?>
    <div class="alert alert-info">Belum ada designer terdaftar. Silakan tambahkan data designer ke database agar dapat ditampilkan di halaman ini.</div>
  <?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
      <?php foreach ($designers as $designer): ?>
        <div class="col">
          <div class="card shadow-sm border-0 h-100">
            <img src="/desainIn/assets/images/founder.png" class="card-img-top" alt="<?= htmlspecialchars($designer['full_name']); ?>">
            <div class="card-body">
              <h5 class="card-title mb-2"><?= htmlspecialchars($designer['full_name']); ?></h5>
              <p class="text-muted mb-3"><?= htmlspecialchars($designer['bio'] ?: 'Designer kreatif dengan pengalaman membuat desain visual berkualitas tinggi.'); ?></p>
              <p class="mb-1"><strong>Username:</strong> <?= htmlspecialchars($designer['username']); ?></p>
              <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($designer['email']); ?></p>
              <?php if (!empty($designer['phone'])): ?>
                <p class="mb-0"><strong>Telepon:</strong> <?= htmlspecialchars($designer['phone']); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'designer'): ?>
    <div class="mt-5 alert alert-secondary">
      Sebagai designer terdaftar, lengkapi profil dan bio Anda di halaman <a href="/desainIn/pages/profile.php" class="link-primary">Profil</a> agar klien dapat mengenal Anda lebih baik.
    </div>
  <?php endif; ?>
</div>
