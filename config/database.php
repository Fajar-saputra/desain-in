<?php

$host = "localhost";
$db_name = "design_store";
$username = "root";
$password = "";

try {
    // Membuat koneksi PDO
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);

    // Set error mode ke exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: set encoding UTF-8
    $conn->exec("SET NAMES utf8");

} catch(PDOException $exception) {
    die("Koneksi database gagal: " . $exception->getMessage());
}