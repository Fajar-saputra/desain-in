<?php
require_once __DIR__ . '/../config/helpers.php';

// Ambil data kategori
$categories_query = $conn->query("SELECT * FROM categories");
$all_categories = $categories_query->fetchAll(PDO::FETCH_ASSOC);

// Ambil data produk (services)
$query = $conn->query("
    SELECT services.*, categories.category_name AS category_name 
    FROM services
    LEFT JOIN categories ON services.category_id = categories.id
    ORDER BY services.id DESC
");
$products = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../components/navbar.php'; ?>

<div class="container py-5">
    
    <!-- FILTER BAR (Menggunakan Bootstrap Card & Form) -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <label class="filter-label mb-2">Search</label>
                    <input type="text" class="form-control form-control-sm bg-light border-0" placeholder="Cari desain...">
                </div>
                <div class="col-6 col-md-3">
                    <label class="filter-label mb-2">Category</label>
                    <select class="form-select form-select-sm bg-light border-0">
                        <option selected>Any</option>
                        <?php foreach($all_categories as $cat): ?>
                            <option value="<?= $cat['id']; ?>"><?= $cat['category_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="filter-label mb-2">Format</label>
                    <select class="form-select form-select-sm bg-light border-0">
                        <option>Any</option>
                        <option>Logo</option>
                        <option>Banner</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="filter-label mb-2">Sort</label>
                    <select class="form-select form-select-sm bg-light border-0">
                        <option>Terbaru</option>
                        <option>Harga</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end">
                    <button class="btn btn-sm btn-primary w-100 shadow-sm" style="background-color: #3db4f2; border: none;">
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ACTIVE FILTER BADGE -->
    <div class="mb-4">
        <span class="badge rounded-pill px-3 py-2" style="background-color: #3db4f2;">Romance</span>
    </div>

    <!-- PRODUCT GRID (Menggunakan Bootstrap Row-Cols) -->
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
        <?php foreach ($products as $p): ?>
            <div class="col">
                <div class="product-card-ui h-100" onclick="location.href='detail.php?id=<?= $p['id']; ?>'">
                    <!-- Rasio Gambar 2:3 ala Poster Anime -->
                    <div class="ratio ratio-2x3 mb-3">
                        <img src="../uploads/<?= $p['image_url']; ?>" 
                             class="rounded-3 shadow-sm object-fit-cover" 
                             alt="<?= $p['name']; ?>">
                    </div>
                    
                    <div class="px-1">
                        <h6 class="card-title fw-bold mb-1 text-secondary"><?= $p['name']; ?></h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Rp <?= number_format($p['price_design'], 0, ',', '.'); ?></span>
                        </div>
                        <p class="small text-info mb-0 mt-1" style="font-size: 11px;"><?= $p['category_name']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>