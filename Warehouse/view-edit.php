<?php
require_once 'database.php';
require_once 'gudang.php';

$database = new Database();
$db = $database->getConnection();

$gudang = new Gudang($db);

$gudang->id = isset($_GET['id']) ? $_GET['id'] : die('error : ID tidak ditemukan.');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gudang->name = $_POST['name'];
    $gudang->location = $_POST['location'];
    $gudang->capacity = $_POST['capacity'];
    $gudang->status = $_POST['status'];
    $gudang->opening_hour = $_POST['opening_hour'];
    $gudang->closing_hour = $_POST['closing_hour'];

    if ($gudang->update()) {
        header('Location: index.php');
        exit();
    } else {
        echo "gagal update";
    }
} else {
    $stmt = $gudang->show($gudang->id);
}

ob_start();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gudang</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Gudang</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $gudang->id); ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Gudang:</label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($gudang->name); ?>" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Lokasi:</label>
                <input type="text" class="form-control" name="location" id="location" value="<?php echo htmlspecialchars($gudang->location); ?>" required>
            </div>

            <div class="mb-3">
                <label for="capacity" class="form-label">Kapasitas:</label>
                <input type="number" class="form-control" name="capacity" id="capacity" value="<?php echo $gudang->capacity; ?>" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" name="status" id="status" required>
                    <option value="Aktif" <?php if ($gudang->status == 'Aktif') echo 'selected'; ?>>Aktif</option>
                    <option value="Tidak Aktif" <?php if ($gudang->status == 'Tidak Aktif') echo 'selected'; ?>>Tidak Aktif</option>
                    <option value="Dalam Perbaikan" <?php if ($gudang->status == 'Dalam Perbaikan') echo 'selected'; ?>>Dalam Perbaikan</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="opening_hour" class="form-label">Waktu Buka:</label>
                <input type="time" class="form-control" name="opening_hour" id="opening_hour" value="<?php echo htmlspecialchars($gudang->opening_hour); ?>" required>
            </div>

            <div class="mb-3">
                <label for="closing_hour" class="form-label">Waktu Tutup:</label>
                <input type="time" class="form-control" name="closing_hour" id="closing_hour" value="<?php echo htmlspecialchars($gudang->closing_hour); ?>" required>
            </div>

            <button type="submit" class="btn btn-warning w-100">Update Gudang</button>
        </form>

        <br>
        <a href="index.php" class="btn btn-secondary">Kembali ke Daftar Gudang</a>
    </div>

    <!-- Bootstrap JS CDN (Opsional, jika Anda memerlukan komponen Bootstrap yang menggunakan JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();


echo $content;
?>