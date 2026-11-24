<?php
require_once 'config/Database.php';
require_once 'classes/Barang.php';

$db = new Database();
$barang = new Barang($db->conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_data'])) {
    $barang->simpan(
        $_POST['kategori_id'],
        $_POST['supplier_id'],
        $_POST['nama_barang'],
        $_POST['harga'],
        $_POST['stok']
    );
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_data'])) {
    $barang->ubah(
        $_POST['id'],
        $_POST['kategori_id'],
        $_POST['supplier_id'],
        $_POST['nama_barang'],
        $_POST['harga'],
        $_POST['stok']
    );
    header("Location: index.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $barang->hapus($_GET['hapus']);
    header("Location: index.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $editData = $barang->ambilById($_GET['edit']);
}

$listKategori = $barang->getKategori();
$listSupplier = $barang->getSupplier();
$listBarang = $barang->ambilSemua();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stok Barang Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h4 class="mb-3">Data Stok Barang</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" class="row g-2">
                <?php if ($editData): ?>
                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                <?php endif; ?>

                <div class="col-md-2">
                    <select name="kategori_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($listKategori as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= ($editData && $editData['kategori_id'] == $k['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="supplier_id" class="form-select" required>
                        <option value="">Pilih Supplier</option>
                        <?php foreach ($listSupplier as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= ($editData && $editData['supplier_id'] == $s['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nama_supplier']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" name="nama_barang" class="form-control" placeholder="Nama barang"
                           value="<?= $editData ? htmlspecialchars($editData['nama_barang']) : '' ?>" required>
                </div>

                <div class="col-md-2">
                    <input type="number" name="harga" class="form-control" placeholder="Harga"
                           value="<?= $editData ? $editData['harga'] : '' ?>" required>
                </div>

                <div class="col-md-1">
                    <input type="number" name="stok" class="form-control" placeholder="Stok"
                           value="<?= $editData ? $editData['stok'] : '' ?>" required>
                </div>

                <div class="col-md-2">
                    <?php if ($editData): ?>
                        <button type="submit" name="update_data" class="btn btn-warning w-100">Simpan Edit</button>
                    <?php else: ?>
                        <button type="submit" name="simpan_data" class="btn btn-primary w-100">Tambah</button>
                    <?php endif; ?>
                </div>
            </form>

            <?php if ($editData): ?>
                <div class="mt-2">
                    <a href="index.php" class="btn btn-sm btn-secondary">Batal</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Total Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($listBarang) == 0): ?>
                        <tr>
                            <td colspan="9" class="text-center">Data masih kosong</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($listBarang as $b): ?>
                            <?php $st = IndukData::statusStok($b['stok']); ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($b['nama_kategori']) ?></td>
                                <td><?= htmlspecialchars($b['nama_supplier']) ?> (<?= htmlspecialchars($b['kota']) ?>)</td>
                                <td><?= htmlspecialchars($b['nama_barang']) ?></td>
                                <td>Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                                <td><?= $b['stok'] ?></td>
                                <td>
                                    <span class="badge <?= $st == 'Aman' ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $st ?>
                                    </span>
                                </td>
                                <td>Rp <?= number_format($b['total_nilai'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="index.php?edit=<?= $b['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="index.php?hapus=<?= $b['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>