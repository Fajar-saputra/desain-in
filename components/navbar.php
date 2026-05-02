<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/desainIn/assets/css/styles.css">

<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
  <div class="container">
    <a class="navbar-brand" href="/desainIn/index.php">
      <img src="/desainIn/assets/images/logo-brand.jpeg" alt="DesainIn" style="height: 45px;">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto gap-lg-4">
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2" href="/desainIn/index.php">
            <img src="/desainIn/assets/icons/users.png" class="nav-icon" alt=""> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2" href="/desainIn/pages/service.php">
            <img src="/desainIn/assets/icons/document.png" class="nav-icon" alt=""> Service
          </a>
        </li>
        <?php $role = $_SESSION['user']['role'] ?? null; ?>
        <?php if ($role === 'user'): ?>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2" href="/desainIn/pages/myOrder.php">
              <img src="/desainIn/assets/icons/online-shopping.png" class="nav-icon" alt=""> Pesanan Saya
            </a>
          </li>
        <?php elseif ($role === 'designer'): ?>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2" href="/desainIn/pages/designer.php">
              <img src="/desainIn/assets/icons/users.png" class="nav-icon" alt=""> Designer
            </a>
          </li>
        <?php elseif ($role === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2" href="/desainIn/pages/admin_dashboard.php">
              <img src="/desainIn/assets/icons/users.png" class="nav-icon" alt=""> Admin
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2" href="#">
              <img src="/desainIn/assets/icons/users.png" class="nav-icon" alt=""> Designer
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2" href="/desainIn/pages/profile.php">
            <img src="/desainIn/assets/icons/users.png" class="nav-icon" alt=""> Profil
          </a>
        </li>
      </ul>

      <div class="d-flex align-items-center">
        <?php if (isset($_SESSION['user'])): ?>
          <span class="me-3">Halo, <?= htmlspecialchars($_SESSION['user']['full_name'] ?? $_SESSION['user']['username']); ?></span>
          <a href="/desainIn/logout.php" class="text-danger text-decoration-none">Logout</a>
        <?php else: ?>
          <a href="#" class="btn-daftar" onclick="showAuthTab('register'); openAuth()">Daftar</a>
          <a href="#" class="btn btn-masuk" onclick="showAuthTab('login'); openAuth()">Masuk</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<?php include __DIR__ . '/login.php'; ?>
<script src="/desainIn/assets/js/script.js"></script>
