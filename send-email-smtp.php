<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validate form data
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Gmail SMTP configuration
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_username = getenv('GMAIL_USERNAME') ?: 'ignacioportfolio@gmail.com';
$smtp_password = getenv('GMAIL_APP_PASSWORD') ?: ''; // Use App Password, not regular password
$from_email = 'ignacioportfolio@gmail.com';
$from_name = 'Portfolio Contact Form';
$to_email = 'ignacioportfolio@gmail.com';

// Create email content
$subject = "New Contact Form Message from $name";
$email_body = "
Name: $name
Email: $email
Message:
$message

---
This message was sent from your portfolio contact form.
";

// Headers for email
$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/plain; charset=UTF-8',
    "From: $from_name <$from_email>",
    "Reply-To: $email",
    "Return-Path: $from_email"
);

// Try to send email using PHP's mail function with Gmail SMTP
// Note: This requires proper SMTP configuration on the server
$mail_sent = false;

// Alternative: Use a more reliable method with proper SMTP authentication
// For production, consider using PHPMailer or similar library
if (!empty($smtp_password)) {
    // Simple SMTP implementation (basic)
    $mail_sent = mail($to_email, $subject, $email_body, implode("\r\n", $headers));
}

// Save to local file for backup
$log_file = 'contact_messages.txt';
$timestamp = date('Y-m-d H:i:s');
$log_entry = "
=== NEW MESSAGE ===
Date: $timestamp
Name: $name
Email: $email
Message: $message
Mail Sent: " . ($mail_sent ? 'Yes' : 'No') . "
==================

";
file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

if ($mail_sent) {
    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully!'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to send email. Please check SMTP configuration.'
    ]);
}
?>