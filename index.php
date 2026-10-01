<?php
require_once '../config/db.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk - Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h2>Product Manager</h2>
    <a href="create.php" class="btn">+ Tambah Produk Baru</a>

    <h3 style="margin-top: 30px;">Daftar Produk</h3>
    <div class="card-grid">
        <?php if (empty($products)): ?>
            <p>Belum ada produk yang tersimpan.</p>
        <?php else: ?>
            <?php foreach ($products as $p): ?>
                <div class="card">
                    <h4><?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?></h4>
                    <p><strong>Kategori:</strong> <?= htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><strong>Harga:</strong> Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
                    <p><strong>Stok:</strong> <?= htmlspecialchars($p['stock'], ENT_QUOTES, 'UTF-8') ?></p>
                    
                    <div class="card-actions">
                        <a href="edit.php?id=<?= $p['id'] ?>" style="color: #38bdf8;">Edit</a>
                        
                        <form action="delete.php" method="POST" style="background:none; padding:0; border:none; margin:0;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>