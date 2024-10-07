<?php
require_once 'database.php';

$database = new Database();
$db = $database->getConnection();

function randomTime() {
    return str_pad(rand(0, 23), 2, '0', STR_PAD_LEFT) . ':' . 
           str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':00';
}

function randomGudangName() {
    $prefixes = ['Gudang', 'Warehouse', 'Storage'];
    $suffixes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
    return $prefixes[array_rand($prefixes)] . ' ' . $suffixes[array_rand($suffixes)] . ' ' . rand(1, 100);
}

function randomLocation() {
    $cities = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Palembang', 'Tangerang', 'Depok', 'Bekasi'];
    return $cities[array_rand($cities)];
}

$stmt = $db->prepare("INSERT INTO gudang (name, location, capacity, status, opening_hour, closing_hour) VALUES (?, ?, ?, ?, ?, ?)");

$total_inserts = 10000;

for ($i = 0; $i < $total_inserts; $i++) {
    $name = randomGudangName();
    $location = randomLocation();
    $capacity = rand(100, 10000);
    $status = ['Aktif', 'Tidak Aktif', 'Dalam Perbaikan'][array_rand(['Aktif', 'Tidak Aktif', 'Dalam Perbaikan'])];
    $opening_hour = randomTime();
    $closing_hour = randomTime();

    $stmt->execute([$name, $location, $capacity, $status, $opening_hour, $closing_hour]);

    if ($i % 100 == 0) {
        echo "Inserted " . ($i + 1) . " records\n";
    }
}

echo "Selesai memasukkan $total_inserts data.";