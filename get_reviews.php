<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
try {
    require_once __DIR__ . '/db.php';
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

$stmt = $conn->prepare(
    'SELECT name, rating, review_text, created_at FROM Testimonials ORDER BY created_at DESC LIMIT 6'
);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed.']);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();
$testimonials = [];

while ($row = $result->fetch_assoc()) {
    $testimonials[] = [
        'name' => $row['name'],
        'rating' => (int) $row['rating'],
        'review_text' => $row['review_text'],
        'created_at' => $row['created_at'],
    ];
}

$stmt->close();

echo json_encode(['testimonials' => $testimonials]);
