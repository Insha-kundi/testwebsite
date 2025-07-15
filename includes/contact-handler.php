<?php
// Contact Form Handler
// Set content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Response array
$response = array(
    'success' => false,
    'message' => ''
);

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

// Sanitize and validate input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Get form data
$name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
$message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';

// Validation
$errors = array();

if (empty($name)) {
    $errors[] = 'Name is required.';
} elseif (strlen($name) < 2) {
    $errors[] = 'Name must be at least 2 characters long.';
}

if (empty($email)) {
    $errors[] = 'Email is required.';
} elseif (!validateEmail($email)) {
    $errors[] = 'Please enter a valid email address.';
}

if (empty($message)) {
    $errors[] = 'Message is required.';
} elseif (strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters long.';
}

// If there are validation errors
if (!empty($errors)) {
    $response['message'] = implode(' ', $errors);
    echo json_encode($response);
    exit;
}

// Email configuration
$to = 'info@yourbrand.com'; // Change this to your email
$subject = 'New Contact Form Submission from ' . $name;
$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=utf-8',
    'From: ' . $name . ' <' . $email . '>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion()
);

// Email template
$emailBody = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 20px; }
        .field { margin-bottom: 15px; }
        .field label { font-weight: bold; display: inline-block; width: 80px; }
        .field value { color: #666; }
        .footer { background: #1f2937; color: white; padding: 15px; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Contact Form Submission</h2>
        </div>
        
        <div class="content">
            <div class="field">
                <label>Name:</label>
                <span class="value">' . htmlspecialchars($name) . '</span>
            </div>
            
            <div class="field">
                <label>Email:</label>
                <span class="value">' . htmlspecialchars($email) . '</span>
            </div>
            
            <div class="field">
                <label>Message:</label>
                <div class="value" style="margin-top: 10px; padding: 15px; background: white; border-left: 4px solid #3b82f6;">
                    ' . nl2br(htmlspecialchars($message)) . '
                </div>
            </div>
            
            <div class="field">
                <label>Date:</label>
                <span class="value">' . date('Y-m-d H:i:s') . '</span>
            </div>
            
            <div class="field">
                <label>IP Address:</label>
                <span class="value">' . $_SERVER['REMOTE_ADDR'] . '</span>
            </div>
        </div>
        
        <div class="footer">
            <p>This email was sent from your website contact form.</p>
        </div>
    </div>
</body>
</html>
';

// Send email
$emailSent = mail($to, $subject, $emailBody, implode("\r\n", $headers));

if ($emailSent) {
    // Log the submission (optional)
    $logEntry = date('Y-m-d H:i:s') . " - Contact form submission from: $name ($email)\n";
    error_log($logEntry, 3, 'contact_log.txt');
    
    $response['success'] = true;
    $response['message'] = 'Thank you for your message! We\'ll get back to you soon.';
} else {
    $response['message'] = 'Sorry, there was an error sending your message. Please try again later.';
}

// Send response
echo json_encode($response);

// Optional: Save to database
// Uncomment and modify the following code to save submissions to a database

/*
// Database configuration
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("INSERT INTO contact_submissions (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $message]);
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}
*/
?>