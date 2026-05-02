<?php
require_once __DIR__ . '/../config/helpers.php';
$services = [];
try {
    $query = 'SELECT s.*, c.category_name, u.username AS designer_name FROM services s LEFT JOIN categories c ON c.id = s.category_id LEFT JOIN users u ON s.designer_id = u.id ORDER BY s.created_at DESC';
    $stmt = $conn->query($query);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $services = [];
}
?>
<?php include __DIR__ . '/../components/navbar.php'; ?>
<div class="container my-5">
  <div class="mb-4">
    <h1 class="fw-bold">Layanan Desain & Cetak</h1>
    <p class="text-muted">Pilih jasa desain terbaik, lengkap dengan opsi cetak dan pengiriman.</p>
  </div>

  <?php if (empty($services)): ?>
    <div class="alert alert-info">Belum ada layanan desain tersedia saat ini.</div>
  <?php else: ?>
    <div class="row gy-4">
      <?php foreach ($services as $service): ?>
        <div class="col-lg-4 col-md-6">
          <div class="card h-100 shadow-sm border-0">
            <div class="ratio ratio-16x9 overflow-hidden rounded-top">
              <img src="<?= htmlspecialchars($service['image_url'] ?: '/desainIn/assets/images/hero-image.png'); ?>" class="card-img-top" alt="<?= htmlspecialchars($service['name']); ?>" style="object-fit: cover;">
            </div>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($service['name']); ?></h5>
              <p class="badge bg-light text-dark border mb-2"><?= htmlspecialchars($service['category_name'] ?: 'Umum'); ?></p>
              <p class="card-text text-muted" style="min-height: 3rem;"><?= htmlspecialchars($service['description']); ?></p>
              <p class="mb-1"><strong>Harga desain:</strong> Rp <?= number_format($service['price_design'], 0, ',', '.'); ?></p>
              <p class="mb-3"><strong>Harga cetak:</strong> Rp <?= number_format($service['price_print'], 0, ',', '.'); ?></p>
              <p class="text-muted small mb-3">Designer: <?= htmlspecialchars($service['designer_name'] ?: 'Tim Desain'); ?></p>
              <?php if (!isset($_SESSION['user'])): ?>
                <button class="btn btn-order w-100" onclick="openAuth()">Pesan Sekarang</button>
              <?php elseif ($_SESSION['user']['role'] === 'user'): ?>
                <a href="/desainIn/pages/payment.php?service_id=<?= $service['id']; ?>" class="btn btn-order w-100">Pesan Sekarang</a>
              <?php else: ?>
                <span class="badge bg-secondary"><?= htmlspecialchars(ucfirst($_SESSION['user']['role'])); ?> tidak dapat memesan</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
