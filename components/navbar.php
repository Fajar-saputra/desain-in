<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/desainIn/assets/css/sytles.css">


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
          <a class="nav-link d-flex align-items-center gap-2" href="#">
            <img src="/desainIn/assets/icons/users.png" class="nav-icon" alt=""> Designer
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2" href="#">
            <img src="/desainIn/assets/icons/document.png" class="nav-icon" alt=""> Service
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2" href="#">
            <img src="/desainIn/assets/icons/users.png" class="nav-icon" alt=""> About Us
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center gap-2" href="#">
            <img src="/desainIn/assets/icons/online-shopping.png" class="nav-icon" alt=""> Pesanan Saya
          </a>
        </li>
      </ul>

      <div class="d-flex align-items-center">
        <?php if (isset($_SESSION['user'])): ?>
          <span class="me-3">Halo, <?= $_SESSION['user']['name']; ?></span>
          <a href="/desainIn/logout.php" class="text-danger text-decoration-none">Logout</a>
        <?php else: ?>
          <a href="/desainIn/pages/register.php" class="btn-daftar">Daftar</a>
          <a href="/desainIn/pages/login.php" class="btn btn-masuk">Masuk</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
