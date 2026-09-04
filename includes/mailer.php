<?php
/**
 * includes/mailer.php — Centralised email sender using PHPMailer + SMTP.
 * SMTP credentials are read from the `settings` database table.
 * Falls back to PHP mail() if SMTP is not configured.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

function send_email_notification(string $toEmail, string $subject, string $htmlBody, string $plainBody = '', string $replyTo = ''): bool {
    $smtpHost     = get_setting('smtp_host', '');
    $smtpPort     = (int) get_setting('smtp_port', '587');
    $smtpUser     = get_setting('smtp_username', '');
    $smtpPass     = get_setting('smtp_password', '');
    $smtpFrom     = get_setting('smtp_from_email', 'info@princeartpackages.com');
    $smtpFromName = get_setting('smtp_from_name', 'Prince Art Packages');
    $smtpSec      = get_setting('smtp_security', 'tls');

    if (empty($smtpHost) || empty($smtpUser)) {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Prince Art Packages <{$smtpFrom}>\r\n";
        if (!empty($replyTo)) { $headers .= "Reply-To: {$replyTo}\r\n"; }
        return @mail($toEmail, $subject, $htmlBody, $headers);
    }

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = ($smtpSec === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtpPort;
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom($smtpFrom, $smtpFromName);
        $mail->addAddress($toEmail);
        if (!empty($replyTo)) { $mail->addReplyTo($replyTo); }
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $plainBody ?: strip_tags(str_replace(['<br>', '<br/>'], "\n", $htmlBody));
        $mail->send();
        return true;
    } catch (PHPMailerException $e) {
        error_log('[PAP Mailer Error] ' . $e->getMessage());
        return false;
    }
}

function build_lead_email_html(array $d, string $adminUrl): string {
    $name    = htmlspecialchars($d['name'] ?? '');
    $company = htmlspecialchars($d['company'] ?? '');
    $email   = htmlspecialchars($d['email'] ?? '');
    $phone   = htmlspecialchars($d['phone'] ?? '');
    $product = htmlspecialchars($d['product'] ?? '');
    $qty     = htmlspecialchars($d['quantity'] ?? '');
    $specs   = nl2br(htmlspecialchars($d['specifications'] ?? ''));
    $message = nl2br(htmlspecialchars($d['message'] ?? ''));
    $sourceBtn  = htmlspecialchars($d['source_button'] ?? 'Website Quote Button');
    $sourcePage = htmlspecialchars($d['source_page'] ?? 'Website');

    return <<<HTML
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>New RFQ — Prince Art Packages</title></head>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;padding:32px 16px;">
<tr><td align="center">
<table width="640" cellpadding="0" cellspacing="0" style="max-width:640px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
<tr><td style="background:linear-gradient(135deg,#0b2545 0%,#173b6c 100%);padding:28px 32px;">
  <p style="margin:0;color:#00a896;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">New Lead Notification</p>
  <h1 style="margin:4px 0 0;color:#fff;font-size:22px;font-weight:800;">New RFQ Inquiry Received</h1>
  <p style="margin:6px 0 0;color:rgba(255,255,255,0.7);font-size:13px;">{$date} &nbsp;|&nbsp; Ref: {$refNo}</p>
</td></tr>
<tr><td style="background:#fff8e1;border-left:4px solid #f59e0b;padding:14px 32px;">
  <p style="margin:0;color:#92400e;font-size:13px;font-weight:600;">Action Required — A pharmaceutical buyer has submitted a quotation request. Please respond within 24 business hours.</p>
</td></tr>
<tr><td style="padding:28px 32px 8px;">
  <h2 style="margin:0 0 16px;color:#0b2545;font-size:16px;font-weight:700;border-bottom:2px solid #e5e7eb;padding-bottom:10px;">Contact Information</h2>
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td width="50%" style="padding-bottom:14px;vertical-align:top;"><span style="display:block;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:3px;">Full Name</span><strong style="font-size:15px;color:#111;">{$name}</strong></td>
      <td width="50%" style="padding-bottom:14px;vertical-align:top;"><span style="display:block;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:3px;">Company</span><strong style="font-size:15px;color:#111;">{$company}</strong></td>
    </tr>
    <tr>
      <td width="50%" style="padding-bottom:14px;vertical-align:top;"><span style="display:block;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:3px;">Email</span><a href="mailto:{$email}" style="font-size:14px;color:#1c7c8c;font-weight:600;text-decoration:none;">{$email}</a></td>
      <td width="50%" style="padding-bottom:14px;vertical-align:top;"><span style="display:block;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:3px;">Phone</span><strong style="font-size:14px;color:#111;">{$phone}</strong></td>
    </tr>
  </table>
</td></tr>
<tr><td style="padding:0 32px 8px;">
  <h2 style="margin:0 0 16px;color:#0b2545;font-size:16px;font-weight:700;border-bottom:2px solid #e5e7eb;padding-bottom:10px;">Packaging Requirement &amp; Lead Source</h2>
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td width="50%" style="padding-bottom:14px;vertical-align:top;"><span style="display:block;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:3px;">Product Category</span><span style="display:inline-block;background:rgba(28,124,140,0.1);color:#1c7c8c;padding:5px 12px;border-radius:20px;font-size:13px;font-weight:700;">{$product}</span></td>
      <td width="50%" style="padding-bottom:14px;vertical-align:top;"><span style="display:block;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:3px;">Estimated Quantity</span><strong style="font-size:14px;color:#111;">{$qty}</strong></td>
    </tr>
    <tr>
      <td width="50%" style="padding-bottom:14px;vertical-align:top;"><span style="display:block;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:3px;">Triggered Button Source</span><span style="display:inline-block;background:#fef3c7;color:#92400e;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">{$sourceBtn}</span></td>
      <td width="50%" style="padding-bottom:14px;vertical-align:top;"><span style="display:block;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:3px;">Triggered Page</span><strong style="font-size:12px;color:#4b5563;">{$sourcePage}</strong></td>
    </tr>
  </table>
</td></tr>
<tr><td style="padding:0 32px 8px;">
  <h2 style="margin:0 0 12px;color:#0b2545;font-size:16px;font-weight:700;border-bottom:2px solid #e5e7eb;padding-bottom:10px;">Technical Specifications</h2>
  <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;font-size:14px;color:#374151;line-height:1.6;">{$specs}</div>
</td></tr>
<tr><td style="padding:14px 32px 8px;">
  <h2 style="margin:0 0 12px;color:#0b2545;font-size:16px;font-weight:700;border-bottom:2px solid #e5e7eb;padding-bottom:10px;">Additional Message</h2>
  <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;font-size:14px;color:#374151;line-height:1.6;">{$message}</div>
</td></tr>
<tr><td style="padding:24px 32px 28px;text-align:center;">
  <a href="{$adminUrl}" style="display:inline-block;background:linear-gradient(135deg,#0b2545,#173b6c);color:#fff;text-decoration:none;font-weight:700;font-size:15px;padding:14px 32px;border-radius:8px;">View Full Lead in Admin Dashboard &rarr;</a>
</td></tr>
<tr><td style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:18px 32px;text-align:center;">
  <p style="margin:0;color:#9ca3af;font-size:12px;line-height:1.6;"><strong style="color:#6b7280;">Prince Art Packages</strong> — Pharmaceutical Secondary Packaging<br>Korangi Creek Industrial Park, Karachi, Pakistan<br><a href="tel:+922138893400" style="color:#1c7c8c;">+92 21-38893400-3</a>&nbsp;|&nbsp;<a href="mailto:info@princeartpackages.com" style="color:#1c7c8c;">info@princeartpackages.com</a></p>
  <p style="margin:8px 0 0;color:#d1d5db;font-size:11px;">This is an automated notification from your website CMS.</p>
</td></tr>
</table>
</td></tr>
</table>
</body></html>
HTML;
}
