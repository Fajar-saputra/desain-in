<?php
require_once __DIR__ . '/../config/helpers.php';
require_role(['user']);

$orders = [];
$stmt = $conn->prepare('SELECT o.*, s.name AS service_name, s.price_design, s.price_print, c.category_name FROM orders o JOIN services s ON s.id = o.service_id LEFT JOIN categories c ON c.id = s.category_id WHERE o.user_id = :user_id ORDER BY o.created_at DESC');
$stmt->execute(['user_id' => $_SESSION['user']['id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include __DIR__ . '/../components/navbar.php'; ?>
<div class="container my-5">
  <div class="mb-4">
    <h1 class="fw-bold">Pesanan Saya</h1>
    <p class="text-muted">Lihat status pesanan, pilihan cetak, dan informasi pembayaran Anda.</p>
  </div>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info">Anda belum memiliki pesanan.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Layanan</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Cetak</th>
            <th>Brief</th>
            <th>Status Pembayaran</th>
            <th>Status Pesanan</th>
            <th>Revisi</th>
            <th>Hasil</th>
            <th>Total</th>
            <th>Dibuat</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $index => $order): ?>
            <tr>
              <td><?= $index + 1; ?></td>
              <td><?= htmlspecialchars($order['service_name']); ?></td>
              <td><?= htmlspecialchars($order['category_name'] ?: 'Umum'); ?></td>
              <td><?= htmlspecialchars($order['quantity']); ?></td>
              <td><?= $order['print_option'] === 'ya' ? 'Ya' : 'Tidak'; ?></td>
              <td><?= htmlspecialchars($order['design_brief'] ?: '-'); ?></td>
              <td><?= htmlspecialchars(ucfirst($order['payment_status'])); ?></td>
              <td><?= htmlspecialchars(ucfirst($order['order_status'])); ?></td>
              <td><?= (int) $order['revision_count']; ?>/5</td>
              <td>
                <?php if (!empty($order['result_file'])): ?>
                  <a href="<?= htmlspecialchars($order['result_file']); ?>" target="_blank">Download</a>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td>Rp <?= number_format($order['total_amount'], 0, ',', '.'); ?></td>
              <td><?= date('d M Y', strtotime($order['created_at'])); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
