<?php
require_once __DIR__ . '/../config/helpers.php';
require_role(['designer']);

$services = [];
$orders = [];

$serviceStmt = $conn->prepare('SELECT * FROM services WHERE designer_id = :designer_id ORDER BY created_at DESC');
$serviceStmt->execute(['designer_id' => $_SESSION['user']['id']]);
$services = $serviceStmt->fetchAll(PDO::FETCH_ASSOC);

$orderStmt = $conn->prepare('SELECT o.*, s.name AS service_name, u.username AS customer_name FROM orders o JOIN services s ON s.id = o.service_id JOIN users u ON u.id = o.user_id WHERE s.designer_id = :designer_id ORDER BY o.created_at DESC LIMIT 10');
$orderStmt->execute(['designer_id' => $_SESSION['user']['id']]);
$orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include __DIR__ . '/../components/navbar.php'; ?>
<div class="container my-5">
  <div class="row gy-4">
    <div class="col-lg-8">
      <h1 class="fw-bold">Dashboard Designer</h1>
      <p class="text-muted">Kelola desain Anda, lihat pesanan, dan terus bangun portofolio layanan.</p>

      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
          <h5 class="card-title">Desain Saya</h5>
          <p class="card-text">Total desain: <strong><?= count($services); ?></strong></p>
        </div>
      </div>

      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
          <h5 class="card-title">Pesanan Terbaru</h5>
          <?php if (empty($orders)): ?>
            <p class="text-muted">Belum ada pesanan untuk desain Anda.</p>
          <?php else: ?>
            <ul class="list-group list-group-flush">
              <?php foreach ($orders as $order): ?>
                <li class="list-group-item">
                  <strong><?= htmlspecialchars($order['service_name']); ?></strong><br>
                  Pelanggan: <?= htmlspecialchars($order['customer_name']); ?>
                  <div class="small text-muted">Status: <?= htmlspecialchars(ucfirst($order['order_status'])); ?> | Pembayaran: <?= htmlspecialchars(ucfirst($order['payment_status'])); ?></div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title">Tambah Desain Baru</h5>
          <?php if ($message = get_flash('auth_error')): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
          <?php endif; ?>
          <?php if ($message = get_flash('auth_success')): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message); ?></div>
          <?php endif; ?>
          <form action="/desainIn/controllers/product.php?action=add" method="POST">
            <input type="text" name="name" class="form-control mb-2" placeholder="Nama desain" required>
            <textarea name="description" class="form-control mb-2" rows="3" placeholder="Deskripsi singkat"></textarea>
            <input type="number" name="price_design" class="form-control mb-2" placeholder="Harga desain" min="0" required>
            <input type="number" name="price_print" class="form-control mb-2" placeholder="Harga cetak" min="0" required>
            <input type="text" name="image_url" class="form-control mb-2" placeholder="URL gambar (opsional)">
            <button class="btn btn-order w-100" type="submit">Unggah Desain</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <h3 class="fw-bold">Daftar Layanan Saya</h3>
    <?php if (empty($services)): ?>
      <div class="alert alert-info">Anda belum menambahkan desain apa pun.</div>
    <?php else: ?>
      <div class="row gy-3">
        <?php foreach ($services as $service): ?>
          <div class="col-md-6">
            <div class="card shadow-sm border-0">
              <div class="card-body">
                <h5><?= htmlspecialchars($service['name']); ?></h5>
                <p class="text-muted small mb-2"><?= htmlspecialchars($service['description']); ?></p>
                <div class="d-flex justify-content-between align-items-center">
                  <span>Desain: Rp <?= number_format($service['price_design'], 0, ',', '.'); ?></span>
                  <span>Cetak: Rp <?= number_format($service['price_print'], 0, ',', '.'); ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
