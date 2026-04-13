<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    
    <!-- Logo -->
    <a class="navbar-brand fw-bold" href="/design-store/index.php">
      DesignStore
    </a>

    <!-- Toggle Mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse" id="navbarNav">
      
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="/design-store/index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="/design-store/pages/products.php">
            <span>
              
            </span>
            Desain
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="/design-store/pages/cart.php">Keranjang</a>
        </li>
      </ul>

      <!-- Right Side -->
      <ul class="navbar-nav">
        
        <?php if (isset($_SESSION['user'])): ?>
          
          <li class="nav-item">
            <span class="nav-link">
              Halo, <?= $_SESSION['user']['name']; ?>
            </span>
          </li>

          <li class="nav-item">
            <a class="nav-link text-danger" href="/design-store/logout.php">Logout</a>
          </li>

        <?php else: ?>
          
          <li class="nav-item">
            <a class="nav-link" href="/design-store/pages/login.php">Login</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/design-store/pages/register.php">Register</a>
          </li>

        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>