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

// For development/local testing - save to file instead of sending email
$log_file = 'contact_messages.txt';
$timestamp = date('Y-m-d H:i:s');
$log_entry = "
=== NEW MESSAGE ===
Date: {$timestamp}
Name: {$name}
Email: {$email}
Message: {$message}
==================

";

// Save message to file
file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

// Also try to send email using Gmail SMTP (requires additional setup)
$to = 'samuelbonadoignacio19@gmail.com';
$subject = 'Portfolio Contact Form - Message from ' . $name;

// Create email content
$email_content = "
New message from your portfolio website:

Name: {$name}
Email: {$email}

Message:
{$message}

---
This message was sent from your portfolio contact form.
Reply-to: {$email}
";

// Try sending with improved headers
$headers = array(
    'From: Portfolio Website <' . $email . '>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8'
);

$mail_sent = mail($to, $subject, $email_content, implode("\r\n", $headers));

// Always return success for now since we're saving to file
echo json_encode([
    'success' => true, 
    'message' => 'Message received successfully! (Check contact_messages.txt file for messages)'
]);

// Log the attempt
error_log("Portfolio contact form submission from: {$name} ({$email}) - Mail function result: " . ($mail_sent ? 'SUCCESS' : 'FAILED'));
?>