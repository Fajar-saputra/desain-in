<?php
require_once __DIR__ . '/../config/helpers.php';
require_role(['admin']);

$counts = [];
$counts['users'] = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$counts['designers'] = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'designer'")->fetchColumn();
$counts['services'] = $conn->query('SELECT COUNT(*) FROM services')->fetchColumn();
$counts['orders'] = $conn->query('SELECT COUNT(*) FROM orders')->fetchColumn();

$recentOrders = $conn->query('SELECT o.*, u.username AS customer_name, s.name AS service_name FROM orders o JOIN users u ON u.id = o.user_id JOIN services s ON s.id = o.service_id ORDER BY o.created_at DESC LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include __DIR__ . '/../components/navbar.php'; ?>
<div class="container my-5">
  <div class="mb-4">
    <h1 class="fw-bold">Admin Dashboard</h1>
    <p class="text-muted">Kelola pengguna, pengaturan jasa, dan pantau status pesanan.</p>
  </div>

  <div class="row gy-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0 p-4">
        <h2><?= $counts['users']; ?></h2>
        <p class="mb-0">Pelanggan</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 p-4">
        <h2><?= $counts['designers']; ?></h2>
        <p class="mb-0">Designer</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 p-4">
        <h2><?= $counts['services']; ?></h2>
        <p class="mb-0">Layanan</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 p-4">
        <h2><?= $counts['orders']; ?></h2>
        <p class="mb-0">Pesanan</p>
      </div>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h5 class="card-title">Pesanan Terbaru</h5>
      <?php if (empty($recentOrders)): ?>
        <p class="text-muted">Belum ada pesanan.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Status</th>
                <th>Pembayaran</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentOrders as $index => $order): ?>
                <tr>
                  <td><?= $index + 1; ?></td>
                  <td><?= htmlspecialchars($order['customer_name']); ?></td>
                  <td><?= htmlspecialchars($order['service_name']); ?></td>
                  <td><?= htmlspecialchars(ucfirst($order['order_status'])); ?></td>
                  <td><?= htmlspecialchars(ucfirst($order['payment_status'])); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
