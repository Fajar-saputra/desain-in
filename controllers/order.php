<?php
session_start();
require_once dirname(__DIR__) . '/config/helpers.php';

$action = $_GET['action'] ?? '';
if ($action !== 'create') {
    header('Location: /desainIn/index.php');
    exit;
}

require_role(['user']);

$serviceId = intval($_POST['service_id'] ?? 0);
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

$insert = $conn->prepare('INSERT INTO orders (user_id, service_id, quantity, print_option, shipping_address, payment_status, order_status, total_amount) VALUES (:user_id, :service_id, :quantity, :print_option, :shipping_address, :payment_status, :order_status, :total_amount)');
$insert->execute([
    'user_id' => $_SESSION['user']['id'],
    'service_id' => $serviceId,
    'quantity' => $quantity,
    'print_option' => $printOption,
    'shipping_address' => $address,
    'payment_status' => $paymentStatus,
    'order_status' => $orderStatus,
    'total_amount' => $totalPrice,
]);

$orderId = $conn->lastInsertId();
flash('auth_success', 'Pesanan telah dibuat. Silakan melanjutkan pembayaran.');
header('Location: /desainIn/pages/payment.php?service_id=' . $serviceId . '&success=1&order_id=' . $orderId);
exit;
