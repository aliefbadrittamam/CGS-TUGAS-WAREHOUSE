<?php
require_once 'database.php';
require_once 'gudang.php';

$database = new Database();
$db = $database->getConnection();

$gudang = new Gudang($db);

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $gudang->name = htmlspecialchars(strip_tags($_POST['name']));
    $gudang->location = htmlspecialchars(strip_tags($_POST['location']));
    $gudang->capacity = filter_var($_POST['capacity'], FILTER_SANITIZE_NUMBER_INT);
    $gudang->status = htmlspecialchars(strip_tags($_POST['status']));
    $gudang->opening_hour = htmlspecialchars(strip_tags($_POST['opening_hour']));
    $gudang->closing_hour = htmlspecialchars(strip_tags($_POST['closing_hour']));

    if ($gudang->create()) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Gagal Menambah Gudang.";
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body class="container">
    <h1 class="text-center">TAMBAH GUDANG</h1>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="mb-2">
            <label for="name">Nama Gudang:</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        
        <div class="mb-2">
            <label for="location">Lokasi Gudang:</label>
            <input type="text" class="form-control" name="location" id="location" required>
        </div>
        
        <div class="mb-2">
            <label for="capacity">Kapasitas Gudang:</label>
            <input type="number" class="form-control" name="capacity" id="capacity" required>
        </div>
        
        <div class="mb-2">
            <label for="status">Status Gudang:</label>
            <select class="form-control" name="status" id="status" required>
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
                <option value="Dalam Perbaikan">Dalam Perbaikan</option>
            </select>
        </div>
        
        <div class="mb-2">
            <label for="opening_hour">Jam Buka:</label>
            <input type="time" class="form-control" name="opening_hour" id="opening_hour" required>
        </div>
        
        <div class="mb-2">
            <label for="closing_hour">Jam Tutup:</label>
            <input type="time" class="form-control" name="closing_hour" id="closing_hour" required>
        </div>
        
        <div>
            <button type="submit" class="btn btn-primary">Tambah Gudang</button>
        </div>
    </form>

    <br>
    <a href="index.php" class="btn btn-secondary">Kembali ke Daftar Gudang</a>

</body>
</html>
<?php
$content = ob_get_clean();


echo $content;
?>