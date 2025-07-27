<?php
header('Content-Type: application/json');

// Only proceed if this is an opt-in request
if ($_POST['opt_in'] !== 'true') {
    echo json_encode(['success' => false, 'error' => 'Not an opt-in request']);
    exit;
}

// Prepare data
$data = [
    'name' => htmlspecialchars($_POST['name'] ?? ''),
    'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
    'phone' => preg_replace('/[^0-9]/', '', $_POST['phone'] ?? ''), // Sanitize phone number
    'studentid' => htmlspecialchars($_POST['studentid'] ?? ''),
    'faculty' => htmlspecialchars($_POST['faculty'] ?? ''),
    'role' => htmlspecialchars($_POST['role'] ?? ''),
    'message' => htmlspecialchars($_POST['message'] ?? ''),
    'opt_in_date' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR']
];

// Validate required fields
if (empty($data['name']) || empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

// Save to file (create directory if needed)
if (!file_exists('submissions')) {
    mkdir('submissions', 0755, true);
}

$filename = 'submissions/optin_' . date('Y-m-d') . '.json';
file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

echo json_encode(['success' => true]);
?>
