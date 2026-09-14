<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/mailer.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
       || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
        exit;
    }
    header('Location: index.php');
    exit;
}

// CSRF Verification
if (!csrf_verify($_POST['csrf_token'] ?? null)) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Session expired. Please refresh the page and try again.']);
        exit;
    }
    header('Location: contact.php?error=csrf');
    exit;
}

$contactName = trim($_POST['contact_name'] ?? '');
$companyName = trim($_POST['company_name'] ?? '');
$email       = trim($_POST['email'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$productType = trim($_POST['product_type'] ?? '');
$quantity    = trim($_POST['estimated_quantity'] ?? '');
$specs       = trim($_POST['specifications'] ?? '');
$message     = trim($_POST['message'] ?? '');
$sourceBtn   = trim($_POST['source_button'] ?? 'Request a Quote Button');
$sourcePage  = trim($_POST['source_page'] ?? ($_SERVER['HTTP_REFERER'] ?? 'Website'));
$ipAddress   = $_SERVER['REMOTE_ADDR'] ?? '';
$userAgent   = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

if ($contactName === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Please provide a valid contact name and business email address.']);
        exit;
    }
    header('Location: contact.php?error=validation');
    exit;
}

try {
    $pdo = get_db();
    $stmt = $pdo->prepare(
        "INSERT INTO leads (name, company, email, phone, product_type, quantity, specifications, message, source_button, source_page, ip_address, user_agent)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $contactName,
        $companyName,
        $email,
        $phone,
        $productType,
        $quantity,
        $specs,
        $message,
        $sourceBtn,
        $sourcePage,
        $ipAddress,
        $userAgent
    ]);

    $leadId = (int) $pdo->lastInsertId();
    $refNo  = 'PAP-RFQ-' . sprintf('%05d', $leadId);

    // HTML Email Notification with Source Button & Page
    $notifyEmail = get_setting('notification_email', 'princeartpackages@gmail.com');
    if (!empty($notifyEmail)) {
        $proto    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $adminUrl = $proto . '://' . ($_SERVER['HTTP_HOST'] ?? 'princeartpackages.com') . '/admin/leads.php';

        $leadData = [
            'name'           => $contactName,
            'company'        => $companyName,
            'email'          => $email,
            'phone'          => $phone,
            'product'        => $productType,
            'quantity'       => $quantity,
            'specifications' => $specs,
            'message'        => $message,
            'source_button'  => $sourceBtn,
            'source_page'    => $sourcePage,
            'ref_no'         => $refNo,
        ];

        $subject   = "New RFQ [{$refNo}] | {$leadData['name']} — {$leadData['company']} | Source: {$leadData['source_button']}";
        $htmlEmail = build_lead_email_html($leadData, $adminUrl);
        $plainText = "New RFQ Lead Received\n\n"
                   . "Ref: {$leadData['ref_no']}\n"
                   . "Name: {$leadData['name']}\n"
                   . "Company: {$leadData['company']}\n"
                   . "Email: {$leadData['email']}\n"
                   . "Phone: {$leadData['phone']}\n"
                   . "Product: {$leadData['product']}\n"
                   . "Quantity: {$leadData['quantity']}\n"
                   . "Source Button: {$leadData['source_button']}\n"
                   . "Source Page: {$leadData['source_page']}\n"
                   . "Specifications: {$leadData['specifications']}\n"
                   . "Message: {$leadData['message']}\n\n"
                   . "Admin: {$adminUrl}";

        send_email_notification($notifyEmail, $subject, $htmlEmail, $plainText, $email);
    }

    // Store lead in session for thank-you.php
    $_SESSION['recent_lead'] = [
        'id'            => $leadId,
        'ref_no'        => $refNo,
        'name'          => $contactName,
        'company'       => $companyName,
        'email'         => $email,
        'product'       => $productType,
        'source_button' => $sourceBtn,
        'source_page'   => $sourcePage
    ];

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success'  => true,
            'ref_no'   => $refNo,
            'redirect' => 'thank-you.php'
        ]);
        exit;
    }

    header('Location: thank-you.php');
    exit;

} catch (Throwable $e) {
    error_log('[PAP RFQ Error] ' . $e->getMessage());
    if ($isAjax) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'We could not submit your request right now. Please try again shortly.']);
        exit;
    }
    header('Location: contact.php?error=dberror');
    exit;
}
