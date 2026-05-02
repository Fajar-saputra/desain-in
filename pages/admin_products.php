<?php
require_once __DIR__ . '/../config/helpers.php';
require_role(['admin']);

$services = $conn->query('SELECT s.*, u.username AS designer_name FROM services s LEFT JOIN users u ON u.id = s.designer_id ORDER BY s.created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include __DIR__ . '/../components/navbar.php'; ?>
<div class="container my-5">
  <div class="mb-4">
    <h1 class="fw-bold">Manajemen Produk</h1>
    <p class="text-muted">Tambah, edit, dan hapus layanan desain yang tersedia di marketplace.</p>
  </div>

  <?php if ($message = get_flash('auth_error')): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <?php if ($message = get_flash('auth_success')): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <div class="row gy-4">
    <div class="col-lg-5">
      <div class="card shadow-sm border-0 p-4">
        <h5 class="mb-3">Tambah Produk Baru</h5>
        <form action="/desainIn/controllers/product.php?action=add" method="POST">
          <input type="text" name="name" class="form-control mb-3" placeholder="Nama layanan" required>
          <textarea name="description" class="form-control mb-3" rows="3" placeholder="Deskripsi singkat"></textarea>
          <input type="number" name="price_design" class="form-control mb-3" placeholder="Harga desain" min="0" required>
          <input type="number" name="price_print" class="form-control mb-3" placeholder="Harga cetak" min="0" required>
          <input type="text" name="image_url" class="form-control mb-3" placeholder="URL gambar (opsional)">
          <input type="number" name="designer_id" class="form-control mb-3" placeholder="ID designer (opsional)">
          <button class="btn btn-order w-100" type="submit">Tambah Produk</button>
        </form>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="card-title">Daftar Produk</h5>
          <?php if (empty($services)): ?>
            <div class="alert alert-info">Belum ada produk desain yang ditambahkan.</div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Designer</th>
                    <th>Harga Desain</th>
                    <th>Cetak</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($services as $index => $service): ?>
                    <tr>
                      <td><?= $index + 1; ?></td>
                      <td><?= htmlspecialchars($service['name']); ?></td>
                      <td><?= htmlspecialchars($service['designer_name'] ?: '-'); ?></td>
                      <td>Rp <?= number_format($service['price_design'], 0, ',', '.'); ?></td>
                      <td>Rp <?= number_format($service['price_print'], 0, ',', '.'); ?></td>
                      <td>
                        <a href="/desainIn/controllers/product.php?action=delete&id=<?= $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?');">Hapus</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
