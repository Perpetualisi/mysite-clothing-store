<?php
require_once __DIR__ . '/db.php';

function getPosts() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM posts ORDER BY id DESC');
    $rows = $stmt->fetchAll();

    $posts = [];
    foreach ($rows as $row) {
        $posts[$row['id']] = [
            'title' => $row['title'],
            'date' => $row['post_date'],
            'excerpt' => $row['excerpt'],
            'content' => $row['content'],
            'image' => $row['image'],
        ];
    }
    return $posts;
}

function getPostById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if (!$row) {
        return null;
    }

    return [
        'title' => $row['title'],
        'date' => $row['post_date'],
        'excerpt' => $row['excerpt'],
        'content' => $row['content'],
        'image' => $row['image'],
    ];
}