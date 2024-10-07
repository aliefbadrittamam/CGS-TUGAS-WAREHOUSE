<?php
require_once 'database.php';
require_once 'gudang.php';

$database = new Database();
$db = $database->getConnection();

$gudang = new Gudang($db);

$search = isset($_GET['search']) ? $_GET['search'] : '';

$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

$stmt = $gudang->read($records_per_page, $offset, $search);
$num = $stmt->rowCount();

$total_rows = $gudang->count($search);
$total_pages = ceil($total_rows / $records_per_page);

$title = "Daftar Gudang";

ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4"><?php echo $title; ?></h1>

        <form action="" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari gudang..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
        </form>
        <a href="view-create.php" class="btn btn-primary mb-3">Tambah Gudang</a>

        <?php
        if ($num > 0) {
            echo '<table class="table table-striped table-hover mt-2">';
            echo '<thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Gudang</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Waktu Buka</th>
                        <th>Waktu Tutup</th>
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>';

            $no = $offset + 1;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$no}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$location}</td>";
                echo "<td>{$capacity}</td>";
                echo "<td>{$status}</td>";
                echo "<td>{$opening_hour}</td>";
                echo "<td>{$closing_hour}</td>";
                echo "<td>";
                echo "<a href='view-edit.php?id={$id}' class='btn btn-sm btn-warning'>Edit</a> ";
                echo "<a href='delete.php?id={$id}' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>";
                echo "</td>";
                echo "</tr>";
                $no++;
            }
            echo "</tbody>";
            echo "</table>";

            echo '<nav aria-label="Page navigation">';
            echo '<ul class="pagination justify-content-center">';

            $prev_page = $page - 1;
            $prev_disabled = ($page <= 1) ? 'disabled' : '';
            echo "<li class='page-item $prev_disabled'>";
            echo "<a class='page-link' href='?page=$prev_page" . ($search ? "&search=$search" : "") . "' tabindex='-1'>Previous</a>";
            echo '</li>';

            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $start_page + 4);
            $start_page = max(1, $end_page - 4);

            if ($start_page > 1) {
                echo "<li class='page-item'><a class='page-link' href='?page=1" . ($search ? "&search=$search" : "") . "'>1</a></li>";
                if ($start_page > 2) {
                    echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                }
            }

            for ($i = $start_page; $i <= $end_page; $i++) {
                $active = ($page == $i) ? 'active' : '';
                echo "<li class='page-item $active'><a class='page-link' href='?page=$i" . ($search ? "&search=$search" : "") . "'>$i</a></li>";
            }

            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                }
                echo "<li class='page-item'><a class='page-link' href='?page=$total_pages" . ($search ? "&search=$search" : "") . "'>$total_pages</a></li>";
            }

            $next_page = $page + 1;
            $next_disabled = ($page >= $total_pages) ? 'disabled' : '';
            echo "<li class='page-item $next_disabled'>";
            echo "<a class='page-link' href='?page=$next_page" . ($search ? "&search=$search" : "") . "'>Next</a>";
            echo '</li>';

            echo '</ul>';
            echo '</nav>';
        } else {
            echo "<p class='alert alert-info'>Tidak ada data gudang.</p>";
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
<?php
$content = ob_get_clean();
echo $content;
?>