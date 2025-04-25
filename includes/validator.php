<?php
class FormValidator {
    private array $data;
    private array $errors = [];
    private string $actionUrl = '';

    public function __construct() {
        $rawInput = file_get_contents('php://input');
        $this->data = json_decode($rawInput, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($this->data)) {
            $this->sendError(['general' => 'Incorrect input format.']);
        }

        $this->actionUrl = trim($this->data['action'] ?? '');
    }

    public function validate(): void {
        $this->validateName();
        $this->validateEmail();
        $this->validateDescription();

        if (!empty($this->errors)) {
            $this->sendError($this->errors);
        }

        $this->sendSuccess();
        $this->forwardToRest();
    }

    private function validateName(): void {
        $name = trim($this->data['name'] ?? '');
        if (empty($name)) {
            $this->errors['name'] = 'Name is required.';
        } elseif (strlen($name) < 3 || strlen($name) > 50) {
            $this->errors['name'] = 'Name must be between 3 and 50 characters long.';
        }
    }

    private function validateEmail(): void {
        $email = trim($this->data['email'] ?? '');
        if (empty($email)) {
            $this->errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email.';
        }
    }

    private function validateDescription(): void {
        $description = trim($this->data['description'] ?? '');
        if (!empty($description) && strlen($description) > 500) {
            $this->errors['description'] = 'The description can be up to 500 characters long.';
        }
    }

    private function sendError(array $errors): void {
        echo json_encode([
            'success' => false,
            'errors' => $errors,
        ]);
        exit;
    }

    private function sendSuccess(): void {
        echo json_encode([
            'success' => true,
        ]);
        exit;
    }

    private function forwardToRest(): void {
        wp_remote_post(rest_url($this->actionUrl), [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode([
                'name'        => $this->data['name'] ?? '',
                'email'       => $this->data['email'] ?? '',
                'description' => $this->data['description'] ?? '',
            ]),
        ]);
    }
}

// Execute validation
$validator = new FormValidator();
$validator->validate();