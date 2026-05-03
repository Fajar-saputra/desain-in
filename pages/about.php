<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="<?= dirname($_SERVER['SCRIPT_NAME'], 2); ?>/assets/css/styles.css">
</head>
<body>

<?php include '../components/navbar.php'; ?>

<div class="container mt-4">
    <div class="about-grid">
        <div class="col">
            <h1 class="fw-bold mb-4 text-3xl">About Us</h1>

            <div class="about-text fs-6" style="line-height: 1.8; color: #333;">
                <p>
                    DesainIn adalah layanan desain grafis berbasis online yang hadir untuk membantu individu, UMKM, dan pelaku bisnis dalam mendapatkan desain visual yang kreatif, profesional, dan mudah diakses. Kami menyediakan sistem pemesanan yang praktis, di mana pelanggan dapat memesan desain secara online dan memilih hasil akhir dalam bentuk soft copy maupun hard copy sesuai kebutuhan.
                </p>
                <p>
                    Dengan menggabungkan kreativitas desainer dan pemanfaatan teknologi digital, DesainIn berfokus pada proses kerja yang efisien, transparan, dan berorientasi pada kepuasan pelanggan. Untuk kebutuhan cetak, kami bekerja sama dengan mitra percetakan terpercaya agar hasil desain dapat diterima pelanggan dengan kualitas yang optimal. DesainIn berkomitmen menjadi solusi desain yang fleksibel, terjangkau, dan relevan dengan perkembangan dunia digital saat ini.
                </p>
            </div>

            <div class="mt-5 fs-6">
                <p class="fst-italic mb-1 fw-light">
                    “Semua orang tu punya bakat, tapi mereka hanya belum memiliki tempat yang tepat untuk mengeluarkannya”
                </p>
                <p class="text-muted ">
                    Refaldo Firdho Alleandro <span class="fw-light">(Founder DesainIn)</span>
                </p>
            </div>
        </div>

        <div class="col text-center">
        <div class="founder-img-wrapper">
            <img src="../assets/images/founder.png" alt="Founder DesainIn" class="founder-img">
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




