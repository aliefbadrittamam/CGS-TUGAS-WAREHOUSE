<?php
require_once 'database.php';
require_once 'gudang.php';

$database = new Database();
$db = $database->getConnection();

$gudang = new Gudang($db);

$gudang->id = isset($_GET['id']) ? $_GET['id'] :die("Id Tidak ditemukan ");
$gudang->delete();

header("Location: index.php");
exit();

?>