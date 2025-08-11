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

// Using EmailJS API to send emails
$emailjs_data = array(
    'service_id' => 'your_service_id',
    'template_id' => 'your_template_id',
    'user_id' => 'your_user_id',
    'template_params' => array(
        'from_name' => $name,
        'from_email' => $email,
        'message' => $message,
        'to_email' => 'samuelbonadoignacio19@gmail.com'
    )
);

// Save to local file for backup
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
file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

// For local development, we'll use a webhook service
// You can use services like Formspree, Netlify Forms, or EmailJS

// Try using a simple CURL request to a webhook service
$webhook_url = "https://formspree.io/f/YOUR_FORM_ID"; // You need to sign up at formspree.io

$curl_data = array(
    'name' => $name,
    'email' => $email,
    'message' => $message
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webhook_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curl_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json'
));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo json_encode([
    'success' => true,
    'message' => 'Message saved locally! For actual email delivery, please set up Formspree or similar service.'
]);
?>