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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$rating = intval($_POST['rating'] ?? 0);
$text = trim($_POST['text'] ?? '');

if (!$name || !$rating || !$text) {
    http_response_code(400);
    echo json_encode(['error' => 'Name, rating, and review text are all required.']);
    exit;
}

if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Rating must be between 1 and 5.']);
    exit;
}

if (mb_strlen($name) > 100 || mb_strlen($text) > 1000) {
    http_response_code(400);
    echo json_encode(['error' => 'Name or review text is too long.']);
    exit;
}

$stmt = $conn->prepare(
    'INSERT INTO Testimonials (name, rating, review_text) VALUES (?, ?, ?)'
);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Database prepare failed.']);
    exit;
}

$stmt->bind_param('sis', $name, $rating, $text);
$executed = $stmt->execute();

if (!$executed) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save testimonial.']);
    $stmt->close();
    exit;
}

$stmt->close();

echo json_encode(['success' => true, 'message' => 'Thank you! Your review has been saved.']);
