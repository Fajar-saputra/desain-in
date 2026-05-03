<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$uriPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '', '/');

function navActive(string $link, string $uriPath): string {
    $link = rtrim($link, '/');
    if ($link === '/desainIn/index.php' && ($uriPath === '/desainIn' || $uriPath === '/desainIn/')) {
        return 'active';
    }
    return strpos($uriPath, $link) !== false ? 'active' : '';
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/desainIn/assets/css/styles.css">

<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
  <div class="container d-flex align-items-center justify-content-between">

    <!-- LOGO -->
    <a class="navbar-brand" href="/desainIn/index.php">
      <img src="/desainIn/assets/images/logo-brand.jpeg" alt="DesainIn" style="height: 45px;">
    </a>

    <!-- TOGGLER -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- NAVBAR -->
    <div class="collapse navbar-collapse d-flex justify-content-between align-items-center w-100" id="navbarNav">

      <!-- MENU TENGAH -->
      <ul class="navbar-nav mx-auto gap-lg-4 d-flex align-items-center">

        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2 <?= navActive('/desainIn/index.php', $uriPath); ?>" href="/desainIn/index.php">
            <img src="/desainIn/assets/icons/home.png" class="nav-icon" alt="">
            
            Home
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2 <?= navActive('/desainIn/pages/service.php', $uriPath); ?>" href="/desainIn/pages/service.php">
            <img src="/desainIn/assets/icons/document.png" class="nav-icon" alt="">
            Service
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2 <?= navActive('/desainIn/pages/about.php', $uriPath); ?>" href="/desainIn/pages/about.php">
            <img src="/desainIn/assets/icons/document.png" class="nav-icon" alt="">
            About
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2 <?= navActive('/desainIn/pages/designer.php', $uriPath); ?>" href="/desainIn/pages/designer.php">
            <img src="/desainIn/assets/icons/users.png" class="nav-icon" alt="">
            Designer
          </a>
        </li>

        <?php $role = $_SESSION['user']['role'] ?? null; ?>

        <?php if ($role === 'user'): ?>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2 <?= navActive('/desainIn/pages/myOrder.php', $uriPath); ?>" href="/desainIn/pages/myOrder.php">
              <img src="/desainIn/assets/icons/online-shopping.png" class="nav-icon" alt="">
              My Order
            </a>
          </li>
        <?php elseif ($role === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2 <?= navActive('/desainIn/pages/admin_dashboard.php', $uriPath); ?>" href="/desainIn/pages/admin_dashboard.php">
              <img src="/desainIn/assets/icons/users.png" class="nav-icon" alt="">
              Admin
            </a>
          </li>
        <?php endif; ?>

      </ul>

      <!-- KANAN -->
      <div class="d-flex align-items-center gap-3 ms-auto">

        <?php if (isset($_SESSION['user'])): ?>

          <a class="nav-link d-flex align-items-center gap-2 <?= navActive('/desainIn/pages/profile.php', $uriPath); ?>" href="/desainIn/pages/profile.php">
            <img src="/desainIn/assets/icons/user.png" class="nav-icon" alt="">
            Account
          </a>

          <a href="/desainIn/logout.php" class="d-flex align-items-center gap-2 text-danger text-decoration-none">
            <img src="/desainIn/assets/icons/logout.png" class="nav-icon" alt="">
            Logout
          </a>

        <?php else: ?>
          <a href="#" class="btn-daftar" onclick="showAuthTab('register'); openAuth()">Register</a>
          <a href="#" class="btn btn-masuk" onclick="showAuthTab('login'); openAuth()">Login</a>
        <?php endif; ?>

      </div>

    </div>
  </div>
</nav>

<?php include __DIR__ . '/login.php'; ?>
<script src="/desainIn/assets/js/script.js"></script>