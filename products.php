<?php
require_once __DIR__ . '/db.php';

function getProducts($category = null, $search = null) {
    global $pdo;

    $sql = 'SELECT * FROM products WHERE 1=1';
    $params = [];

    if ($category) {
        $sql .= ' AND category = ?';
        $params[] = $category;
    }

    if ($search) {
        $sql .= ' AND name LIKE ?';
        $params[] = '%' . $search . '%';
    }

    $sql .= ' ORDER BY id';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $products = [];
    foreach ($rows as $row) {
        $products[$row['id']] = $row;
    }
    return $products;
}

function getCategories() {
    global $pdo;
    $stmt = $pdo->query('SELECT DISTINCT category FROM products ORDER BY category');
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getProductById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    return $product ?: null;
}

function addProduct($name, $price, $image, $description, $category) {
    global $pdo;
    $stmt = $pdo->prepare(
        'INSERT INTO products (name, price, image, description, category) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        trim($name),
        max(0, (int)$price),
        trim($image),
        trim($description),
        trim($category) ?: 'Uncategorized'
    ]);
    return $pdo->lastInsertId();
}

function updateProduct($id, $name, $price, $image, $description, $category) {
    global $pdo;
    $stmt = $pdo->prepare(
        'UPDATE products SET name = ?, price = ?, image = ?, description = ?, category = ? WHERE id = ?'
    );
    $stmt->execute([
        trim($name),
        max(0, (int)$price),
        trim($image),
        trim($description),
        trim($category) ?: 'Uncategorized',
        $id
    ]);
    return $stmt->rowCount() > 0;
}

function deleteProduct($id) {
    global $pdo;

    $product = getProductById($id);
    if ($product && strpos($product['image'], 'uploads/') === 0) {
        $path = __DIR__ . '/' . $product['image'];
        if (file_exists($path)) {
            unlink($path);
        }
    }

    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}