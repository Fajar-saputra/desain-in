<?php
require_once __DIR__ . '/../config/helpers.php';

$action = $_GET['action'] ?? '';
if (!in_array($action, ['add', 'delete'], true)) {
    header('Location: /desainIn/index.php');
    exit;
}

if ($action === 'add') {
    require_role(['admin', 'designer']);
    $categoryId = intval($_POST['category_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $priceDesign = floatval($_POST['price_design'] ?? 0);
    $pricePrint = floatval($_POST['price_print'] ?? 0);
    $imageUrl = trim($_POST['image_url'] ?? '');
    $designerId = $_SESSION['user']['role'] === 'designer' ? $_SESSION['user']['id'] : intval($_POST['designer_id'] ?? 0);
    if ($name === '') {
        flash('auth_error', 'Nama produk tidak boleh kosong.');
        header('Location: ' . ($_SESSION['user']['role'] === 'admin' ? '/desainIn/admin/admin_products.php' : '/desainIn/designer/designer.php'));
        exit;
    }
    $insert = $conn->prepare('INSERT INTO services (category_id, name, description, price_design, price_print, image_url, designer_id) VALUES (:category_id, :name, :description, :price_design, :price_print, :image_url, :designer_id)');
    $insert->execute([
        'category_id' => $categoryId > 0 ? $categoryId : null,
        'name' => $name,
        'description' => $description,
        'price_design' => $priceDesign,
        'price_print' => $pricePrint,
        'image_url' => $imageUrl,
        'designer_id' => $designerId > 0 ? $designerId : null,
    ]);
    flash('auth_success', 'Produk desain berhasil ditambahkan.');
    header('Location: ' . ($_SESSION['user']['role'] === 'admin' ? '/desainIn/admin/admin_products.php' : '/desainIn/designer/designer.php'));
    exit;
}

if ($action === 'delete') {
    require_role(['admin']);
    $serviceId = intval($_GET['id'] ?? 0);
    if ($serviceId > 0) {
        $delete = $conn->prepare('DELETE FROM services WHERE id = :id');
        $delete->execute(['id' => $serviceId]);
        flash('auth_success', 'Produk desain berhasil dihapus.');
    }
    header('Location: /desainIn/admin/admin_products.php');
    exit;
}
