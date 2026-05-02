<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DesignStore</title>
    <!-- link font google and bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>

    <?php include 'components/navbar.php'; ?>

<div class="container mt-5">
    <div class="row align-items-center">
        <!-- Hero Section -->
        <div class="col-lg-6">
            <h1 class="hero-title">Temukan<br>Desain Terbaikmu<br>Disini</h1>
            <p class="text-muted my-4">Lorem ipsum dolor sit amet...</p>
            <?php if (!isset($_SESSION['user'])): ?>
                <button class="btn btn-order" onclick="openAuth()">Order Now</button>
            <?php else: ?>
                <a href="/desainIn/pages/service.php" class="btn btn-order">Order Now</a>
            <?php endif; ?>
        </div>
        <div class="col-lg-6">
            <img src="./assets/images/hero-image.png" class="hero-img shadow" alt="Hero Image">
        </div>
    </div>

    <div class="mt-5 pt-5">
        <h3 class="fw-bold mb-4">Latest Design</h3>
        <div class="row">
            <?php for($i=1; $i<=4; $i++): ?>
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-transparent">
                    <div class="d-flex align-items-center justify-content-center shadow-sm" 
                         style="background-color: #F6F6F6; border-radius: 10px; aspect-ratio: 243 / 194; width: 100%; position: relative; overflow: hidden;">
                        <img src="./assets/images/design-<?php echo $i; ?>.png" 
                             alt="Design" 
                             style="width: 47%; height: 86%; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    </div>
                    <div class="mt-2 ps-1">
                        <h6 class="fw-bold mb-0" style="font-size: 0.95rem;">Indah Cahya Nabila</h6>
                        <small class="text-muted" style="font-size: 0.8rem;">JKT48 9th Generation</small>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div> 
    </div> 
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
