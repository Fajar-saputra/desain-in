<?php
require_once __DIR__ . '/../config/helpers.php';
require_role(['admin']);

$admin = current_user();

$counts = [
  'users' => (int) $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn(),
  'designers' => (int) $conn->query("SELECT COUNT(*) FROM users WHERE role = 'designer'")->fetchColumn(),
  'services' => (int) $conn->query('SELECT COUNT(*) FROM services')->fetchColumn(),
  'orders' => (int) $conn->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
  'paid_orders' => (int) $conn->query("SELECT COUNT(*) FROM orders WHERE payment_status = 'paid'")->fetchColumn(),
  'done_orders' => (int) $conn->query("SELECT COUNT(*) FROM orders WHERE order_status = 'selesai'")->fetchColumn(),
];

$income = (float) $conn->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE payment_status = 'paid'")->fetchColumn();

$recentOrders = $conn->query(
  'SELECT o.*, u.username AS customer_name, s.name AS service_name
   FROM orders o
   JOIN users u ON u.id = o.user_id
   JOIN services s ON s.id = o.service_id
   ORDER BY o.created_at DESC
   LIMIT 6'
)->fetchAll(PDO::FETCH_ASSOC);

function admin_status_label($status) {
  $labels = [
    'baru' => 'Baru',
    'diproses' => 'Diproses',
    'selesai' => 'Selesai',
    'dibatalkan' => 'Dibatalkan',
    'pending' => 'Pending',
    'paid' => 'Paid',
    'cancelled' => 'Cancelled',
  ];

  return $labels[$status] ?? ucfirst((string) $status);
}

function order_progress($status) {
  $progress = [
    'baru' => 20,
    'diproses' => 65,
    'selesai' => 100,
    'dibatalkan' => 0,
  ];

  return $progress[$status] ?? 10;
}

$stats = [
  [
    'title' => 'Pelanggan',
    'value' => number_format($counts['users']),
    'meta' => $counts['done_orders'] . ' pesanan selesai',
    'icon' => 'US',
  ],
  [
    'title' => 'Pesanan',
    'value' => number_format($counts['orders']),
    'meta' => $counts['paid_orders'] . ' sudah dibayar',
    'icon' => 'OR',
  ],
  [
    'title' => 'Layanan',
    'value' => number_format($counts['services']),
    'meta' => $counts['designers'] . ' designer aktif',
    'icon' => 'SV',
  ],
  [
    'title' => 'Pendapatan',
    'value' => 'Rp ' . number_format($income, 0, ',', '.'),
    'meta' => 'Dari pembayaran paid',
    'icon' => 'RP',
  ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - DesainIn</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/desainIn/assets/css/styles.css">
</head>
<body class="admin-body">
  <div class="admin-shell">
    <aside class="admin-sidebar">
      <a class="admin-brand" href="/desainIn/pages/admin_dashboard.php">DesainIn</a>

      <nav class="admin-menu" aria-label="Admin navigation">
        <a class="admin-menu-link active" href="/desainIn/pages/admin_dashboard.php">
          <span class="admin-menu-icon">D</span>
          Dashboard
        </a>

        <p class="admin-menu-label">Manajemen</p>
        <a class="admin-menu-link" href="/desainIn/pages/admin_products.php">
          <span class="admin-menu-icon">P</span>
          Produk
        </a>
        <a class="admin-menu-link" href="#recent-orders">
          <span class="admin-menu-icon">O</span>
          Pesanan
        </a>
        <a class="admin-menu-link" href="#recent-orders">
          <span class="admin-menu-icon">G</span>
          Designer
        </a>

        <p class="admin-menu-label">Akun</p>
        <a class="admin-menu-link" href="/desainIn/pages/profile.php">
          <span class="admin-menu-icon">A</span>
          Profil
        </a>
        <a class="admin-menu-link" href="/desainIn/logout.php">
          <span class="admin-menu-icon">L</span>
          Logout
        </a>
      </nav>
    </aside>

    <main class="admin-main">
      <header class="admin-topbar">
        <button class="admin-menu-button" type="button" aria-label="Buka menu">=</button>
        <form class="admin-search" action="/desainIn/pages/admin_dashboard.php" method="GET">
          <input type="search" name="q" placeholder="Search">
        </form>
        <div class="admin-user">
          <span class="admin-bell" aria-hidden="true">!</span>
          <span class="admin-avatar"><?= strtoupper(substr($admin['username'] ?? 'A', 0, 1)); ?></span>
        </div>
      </header>

      <section class="admin-hero">
        <div>
          <p class="admin-eyebrow">Overview</p>
          <h1>Dashboard Admin</h1>
        </div>
        <a class="admin-primary-action" href="/desainIn/pages/admin_products.php">Tambah Produk</a>
      </section>

      <section class="admin-content">
        <div class="admin-stat-grid">
          <?php foreach ($stats as $stat): ?>
            <article class="admin-stat-card">
              <div class="admin-stat-head">
                <p><?= htmlspecialchars($stat['title']); ?></p>
                <span><?= htmlspecialchars($stat['icon']); ?></span>
              </div>
              <strong><?= htmlspecialchars($stat['value']); ?></strong>
              <small><?= htmlspecialchars($stat['meta']); ?></small>
            </article>
          <?php endforeach; ?>
        </div>

        <section class="admin-panel" id="recent-orders">
          <div class="admin-panel-header">
            <div>
              <h2>Pesanan Terbaru</h2>
              <p>Monitoring order yang masuk ke marketplace desain.</p>
            </div>
            <span><?= number_format($counts['orders']); ?> total</span>
          </div>

          <?php if (empty($recentOrders)): ?>
            <div class="admin-empty-state">
              <h3>Belum ada pesanan.</h3>
              <p>Pesanan baru akan muncul di tabel ini setelah pelanggan melakukan checkout.</p>
            </div>
          <?php else: ?>
            <div class="admin-table-wrap">
              <table class="admin-table">
                <thead>
                  <tr>
                    <th>Layanan</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Progress</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recentOrders as $order): ?>
                    <?php $progress = order_progress($order['order_status']); ?>
                    <tr>
                      <td>
                        <div class="admin-project-cell">
                          <span><?= strtoupper(substr($order['service_name'], 0, 2)); ?></span>
                          <div>
                            <strong><?= htmlspecialchars($order['service_name']); ?></strong>
                            <small><?= date('d M Y', strtotime($order['created_at'])); ?></small>
                          </div>
                        </div>
                      </td>
                      <td><?= htmlspecialchars($order['customer_name']); ?></td>
                      <td>Rp <?= number_format($order['total_amount'], 0, ',', '.'); ?></td>
                      <td>
                        <span class="admin-badge admin-badge-<?= htmlspecialchars($order['payment_status']); ?>">
                          <?= htmlspecialchars(admin_status_label($order['payment_status'])); ?>
                        </span>
                      </td>
                      <td><?= htmlspecialchars(admin_status_label($order['order_status'])); ?></td>
                      <td>
                        <div class="admin-progress">
                          <span><?= $progress; ?>%</span>
                          <div><i style="width: <?= $progress; ?>%;"></i></div>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </section>
      </section>
    </main>
  </div>
</body>
</html>
