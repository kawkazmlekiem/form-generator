<?php
require_once(dirname(__FILE__, 5) . '\wp-load.php');
header('Content-Type: application/json');

$raw_input = file_get_contents('php://input');
$data = json_decode($raw_input, true);
$actionUrl = trim($data['action'] ?? '');

// Validate JSON correctness
if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
    echo json_encode([
        'success' => false,
        'errors' => ['general' => 'Incorrect input format.']
    ]);
    exit;
}

// Get data
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$description = trim($data['description'] ?? '');

// Validation
$errors = [];

if (empty($name)) {
    $errors['name'] = 'Name is required.';
} elseif (strlen($name) < 3 || strlen($name) > 50) {
    $errors['name'] = 'Name must be between 3 and 50 characters long.';
}

if (empty($email)) {
    $errors['email'] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email.';
}

if (!empty($description) && strlen($description) > 500) {
    $errors['description'] = 'The description can be up to 500 characters long.';
}

// Return response to JS
if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'errors' => $errors
    ]);
    exit;
} else {
    echo json_encode([
        'success' => true
    ]);
}

// Sending data to WordPress REST API
$response = wp_remote_post(rest_url($actionUrl), [
    'method'  => 'POST',
    'headers' => ['Content-Type' => 'application/json'],
    'body'    => json_encode([
        'name' => $name,
        'email' => $email,
        'description' => $description
    ]),
]);
