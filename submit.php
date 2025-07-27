<?php
// Set headers for JSON response
header('Content-Type: application/json');

// Collect all form data
$data = [
    'name' => $_POST['name'] ?? '',
    'email' => $_POST['email'] ?? '',
    'studentid' => $_POST['studentid'] ?? '',
    'faculty' => $_POST['faculty'] ?? '',
    'role' => $_POST['role'] ?? '',
    'prewritten' => $_POST['prewritten'] ?? '',
    'message' => $_POST['message'] ?? '',
    'timestamp' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR']
];

// Validate required fields
if (empty($data['name']) || empty($data['email']) || empty($data['studentid'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Required fields missing']);
    exit;
}

// Create submissions directory if it doesn't exist
if (!file_exists('submissions')) {
    mkdir('submissions', 0755, true);
}

// Save to JSON file (one file per submission)
$filename = 'submissions/submission_' . time() . '_' . bin2hex(random_bytes(4)) . '.json';
file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));

// Alternatively, append to a single file (simpler but less organized)
// file_put_contents('submissions/all_submissions.json', json_encode($data)."\n", FILE_APPEND);

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Submission saved successfully'
]);
?>
