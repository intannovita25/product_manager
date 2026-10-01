<?php
require_once '../config/db.php';

$errors = [];
$id = filter_var($_GET['id'] ?? $_POST['id'] ?? null, FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit;
}

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
        $stmtCheck = $pdo->prepare("SELECT id FROM products WHERE name = ? AND id != ?");
        $stmtCheck->execute([$name, $id]);
        if ($stmtCheck->fetch()) {
            $errors[] = "Nama produk sudah terdaftar, gunakan nama lain.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, price = ?, stock = ? WHERE id = ?");
        $stmt->execute([$name, $category, $price, $stock, $id]);

        header("Location: index.php");
        exit;
    }
    
    $product['name'] = $name;
    $product['category'] = $category;
    $product['price'] = $price;
    $product['stock'] = $stock;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h2>Edit Produk</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $e): ?>
                <p style="margin:2px 0;"><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="edit.php?id=<?= $id ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Nama Produk</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Kategori</label>
        <input type="text" name="category" value="<?= htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Harga</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label>Stok</label>
        <input type="number" name="stock" value="<?= htmlspecialchars($product['stock'], ENT_QUOTES, 'UTF-8') ?>" required>

        <br><br>
        <button type="submit" class="btn">Update Produk</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>