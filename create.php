<?php
require_once '../config/db.php';

$errors = [];
$name = $category = $price = $stock = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Validasi CSRF Token Gagal!");
    }

    $name     = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);
    $stock    = filter_var($_POST['stock'] ?? 0, FILTER_VALIDATE_INT);

    if (mb_strlen($name) < 3) {
        $errors[] = "Nama produk minimal 3 karakter.";
    }
    if ($price <= 0) {
        $errors[] = "Harga harus lebih besar dari 0.";
    }
    if ($stock < 0) {
        $errors[] = "Stok tidak boleh negatif.";
    }

    if (empty($errors)) {
        $stmtCheck = $pdo->prepare("SELECT id FROM products WHERE name = ?");
        $stmtCheck->execute([$name]);
        if ($stmtCheck->fetch()) {
            $errors[] = "Nama produk sudah terdaftar, gunakan nama lain.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $category, $price, $stock]);

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h2>Tambah Produk Baru</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $e): ?>
                <p style="margin:2px 0;"><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="create.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <label>Nama Produk</label>
        <input type="text" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Kategori</label>
        <input type="text" name="category" value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Harga</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price, ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Stok</label>
        <input type="number" name="stock" value="<?= htmlspecialchars($stock, ENT_QUOTES, 'UTF-8') ?>" required>

        <br><br>
        <button type="submit" class="btn">Simpan Produk</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>