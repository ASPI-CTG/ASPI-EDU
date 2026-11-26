<?php
declare(strict_types=1);
require_once __DIR__ . '/app/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('X-Content-Type-Options: nosniff');
try {
    $limit = max(1, min(6, (int)($_GET['limit'] ?? 3)));
    $stmt = $pdo->prepare("SELECT id, title, notice_date, description FROM notices WHERE status='published' ORDER BY notice_date DESC, id DESC LIMIT :lim");
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $notices = [];
    foreach ($rows as $row) {
        $timestamp = strtotime((string)$row['notice_date']);
        $notices[] = [
            'id' => (int)$row['id'],
            'title' => (string)$row['title'],
            'notice_date' => (string)$row['notice_date'],
            'date_bn' => $timestamp ? date('d-m-Y', $timestamp) : (string)$row['notice_date'],
            'description' => (string)$row['description'],
        ];
    }
    echo json_encode(['success' => true, 'notices' => $notices], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'notices' => [], 'message' => 'বিজ্ঞপ্তি লোড করা যায়নি।'], JSON_UNESCAPED_UNICODE);
}
