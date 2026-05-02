<?php
session_start();
require_once dirname(__DIR__) . '/config/helpers.php';

$action = $_GET['action'] ?? '';
if ($action !== 'create') {
    header('Location: /desainIn/index.php');
    exit;
}

require_role(['user']);

function save_order_upload($fieldName) {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
    $originalName = $_FILES[$fieldName]['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        return null;
    }

    $uploadDir = dirname(__DIR__) . '/assets/uploads/orders';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = $fieldName . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $targetPath = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
        return null;
    }

    return '/desainIn/assets/uploads/orders/' . $fileName;
}

$serviceId = intval($_POST['service_id'] ?? 0);
$designBrief = trim($_POST['design_brief'] ?? '');
$quantity = max(1, intval($_POST['quantity'] ?? 1));
$printOption = ($_POST['print_option'] ?? 'tidak') === 'ya' ? 'ya' : 'tidak';
$paymentMethod = in_array($_POST['payment_method'] ?? '', ['bank_transfer', 'cod', 'ewallet'], true) ? $_POST['payment_method'] : 'bank_transfer';
$address = trim($_POST['shipping_address'] ?? '');

if ($serviceId <= 0 || $address === '') {
    flash('auth_error', 'Layanan dan alamat pengiriman harus diisi.');
    header('Location: /desainIn/pages/payment.php?service_id=' . $serviceId);
    exit;
}

$stmt = $conn->prepare('SELECT * FROM services WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $serviceId]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$service) {
    flash('auth_error', 'Layanan tidak ditemukan.');
    header('Location: /desainIn/pages/service.php');
    exit;
}

$totalPrice = $service['price_design'] * $quantity;
if ($printOption === 'ya') {
    $totalPrice += $service['price_print'] * $quantity;
}

$paymentStatus = 'pending';
$orderStatus = 'baru';
$referenceFile = save_order_upload('reference_file');
$proofOfPayment = save_order_upload('proof_of_payment');

$insert = $conn->prepare('INSERT INTO orders (user_id, service_id, design_brief, reference_file, quantity, print_option, shipping_address, total_amount, proof_of_payment, payment_status, order_status) VALUES (:user_id, :service_id, :design_brief, :reference_file, :quantity, :print_option, :shipping_address, :total_amount, :proof_of_payment, :payment_status, :order_status)');
$insert->execute([
    'user_id' => $_SESSION['user']['id'],
    'service_id' => $serviceId,
    'design_brief' => $designBrief !== '' ? $designBrief : null,
    'reference_file' => $referenceFile,
    'quantity' => $quantity,
    'print_option' => $printOption,
    'shipping_address' => $address,
    'total_amount' => $totalPrice,
    'proof_of_payment' => $proofOfPayment,
    'payment_status' => $paymentStatus,
    'order_status' => $orderStatus,
]);

$orderId = $conn->lastInsertId();
flash('auth_success', 'Pesanan telah dibuat. Silakan melanjutkan pembayaran.');
header('Location: /desainIn/pages/payment.php?service_id=' . $serviceId . '&success=1&order_id=' . $orderId);
exit;
