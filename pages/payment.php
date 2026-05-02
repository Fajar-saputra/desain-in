<?php
require_once __DIR__ . '/../config/helpers.php';
require_role(['user']);

$serviceId = intval($_GET['service_id'] ?? 0);
$success = isset($_GET['success']) && $_GET['success'] === '1';
$orderId = intval($_GET['order_id'] ?? 0);
$service = null;

if ($serviceId > 0) {
    $stmt = $conn->prepare('SELECT * FROM services WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $serviceId]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$service) {
    header('Location: /desainIn/pages/service.php');
    exit;
}
?>
<?php include __DIR__ . '/../components/navbar.php'; ?>
<div class="container my-5">
  <div class="row">
    <div class="col-lg-8 mx-auto">
      <h1 class="fw-bold mb-3">Pembayaran untuk <?= htmlspecialchars($service['name']); ?></h1>
      <p class="text-muted">Rekomendasi pemula: gunakan Bank Transfer atau e-wallet, karena mudah diterapkan tanpa integrasi kompleks.</p>

      <?php if ($message = get_flash('auth_error')): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
      <?php endif; ?>
      <?php if ($message = get_flash('auth_success')): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
          <h5 class="card-title">Ringkasan Pesanan</h5>
          <p class="mb-1"><strong>Layanan:</strong> <?= htmlspecialchars($service['name']); ?></p>
          <p class="mb-1"><strong>Harga desain:</strong> Rp <?= number_format($service['price_design'], 0, ',', '.'); ?></p>
          <p class="mb-1"><strong> Harga cetak:</strong> Rp <?= number_format($service['price_print'], 0, ',', '.'); ?> (opsional)</p>
          <p class="mb-1"><strong>Deskripsi:</strong> <?= htmlspecialchars($service['description']); ?></p>
        </div>
      </div>

      <div class="card shadow-sm border-0 p-4">
        <h5 class="mb-3">Metode Pembayaran</h5>
        <form action="/desainIn/controllers/order.php?action=create" method="POST">
          <input type="hidden" name="service_id" value="<?= $service['id']; ?>">
          <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" name="quantity" class="form-control" min="1" value="1" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Opsi cetak</label>
            <select name="print_option" class="form-select">
              <option value="tidak">Tidak</option>
              <option value="ya">Ya</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat pengiriman</label>
            <textarea name="shipping_address" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Metode pembayaran</label>
            <select name="payment_method" class="form-select">
              <option value="bank_transfer">Bank Transfer</option>
              <option value="ewallet">E-Wallet</option>
              <option value="cod">Cash on Delivery</option>
            </select>
          </div>
          <button class="btn btn-order w-100" type="submit">Bayar Sekarang</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php if ($success && $orderId > 0): ?>
<div class="thankyou-modal" id="thankyouModal">
  <div class="thankyou-box">
    <h2>Terima kasih!</h2>
    <p>Pemesanan Anda telah berhasil dibuat.</p>
    <p>Nomor pesanan: <strong>#<?= $orderId; ?></strong></p>
    <p>Status order: <strong>Menunggu pembayaran</strong></p>
    <a href="/desainIn/pages/myOrder.php" class="btn btn-order mt-3">Lihat Pesanan Saya</a>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('thankyouModal');
    if (modal) {
      modal.style.display = 'flex';
      modal.addEventListener('click', function (event) {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      });
    }
  });
</script>
<?php endif; ?>
