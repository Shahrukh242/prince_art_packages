<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate or retain robot challenge token
if (empty($_SESSION['robot_challenge_token'])) {
    $_SESSION['robot_challenge_token'] = bin2hex(random_bytes(16));
}
$challengeToken = $_SESSION['robot_challenge_token'];

$captchaType        = get_setting('admin_captcha_type', 'turnstile');
$turnstileSiteKey   = get_setting('turnstile_site_key', '1x00000000000000000000BB');
$turnstileSecretKey = get_setting('turnstile_secret_key', '1x0000000000000000000000000000000AA');
$recaptchaSiteKey   = get_setting('recaptcha_site_key', '');
$recaptchaSecretKey = get_setting('recaptcha_secret_key', '');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Session expired, please try again.';
    } else {
        $captchaValid = false;
        $captchaErrorMsg = '';

        if ($captchaType === 'disabled') {
            $captchaValid = true;
        } elseif ($captchaType === 'turnstile') {
            $cfToken  = trim($_POST['cf-turnstile-response'] ?? '');
            $fallback = trim($_POST['built_in_robot_check'] ?? '');

            if (!empty($cfToken)) {
                $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'secret'   => $turnstileSecretKey ?: '1x0000000000000000000000000000000AA',
                    'response' => $cfToken,
                    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
                ]));
                curl_setopt($ch, CURLOPT_TIMEOUT, 6);
                $resp = curl_exec($ch);
                $curlErr = curl_error($ch);
                curl_close($ch);

                if ($resp) {
                    $json = json_decode($resp, true);
                    if (!empty($json['success'])) {
                        $captchaValid = true;
                    } else {
                        $captchaErrorMsg = 'Security verification failed. Please try checking the verification box again.';
                    }
                } elseif ($curlErr && strlen($cfToken) > 10) {
                    // Outbound connection error (offline dev), client submitted turnstile token
                    $captchaValid = true;
                } else {
                    $captchaErrorMsg = 'Unable to verify security challenge. Please try again.';
                }
            } elseif (!empty($fallback) && hash_equals($challengeToken, $fallback)) {
                $captchaValid = true;
            } else {
                $captchaErrorMsg = 'Please check the verification box before logging in.';
            }
        } elseif ($captchaType === 'recaptcha') {
            $gToken   = trim($_POST['g-recaptcha-response'] ?? '');
            $fallback = trim($_POST['built_in_robot_check'] ?? '');

            if (!empty($gToken)) {
                $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'secret'   => $recaptchaSecretKey,
                    'response' => $gToken,
                    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
                ]));
                curl_setopt($ch, CURLOPT_TIMEOUT, 6);
                $resp = curl_exec($ch);
                curl_close($ch);

                if ($resp) {
                    $json = json_decode($resp, true);
                    if (!empty($json['success'])) {
                        $captchaValid = true;
                    } else {
                        $captchaErrorMsg = 'Google reCAPTCHA verification failed. Please check the box again.';
                    }
                }
            } elseif (!empty($fallback) && hash_equals($challengeToken, $fallback)) {
                $captchaValid = true;
            } else {
                $captchaErrorMsg = 'Please check "I\'m not a robot" before logging in.';
            }
        } elseif ($captchaType === 'built_in') {
            $fallback = trim($_POST['built_in_robot_check'] ?? '');
            if (!empty($fallback) && hash_equals($challengeToken, $fallback)) {
                $captchaValid = true;
            } else {
                $captchaErrorMsg = 'Please check "I\'m not a robot" to verify security.';
            }
        }

        if (!$captchaValid) {
            $error = $captchaErrorMsg ?: 'Please complete the security check.';
        } elseif (attempt_login($_POST['username'] ?? '', $_POST['password'] ?? '')) {
            unset($_SESSION['robot_challenge_token']);
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Prince Art Packages</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <?php if ($captchaType === 'turnstile'): ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php elseif ($captchaType === 'recaptcha' && !empty($recaptchaSiteKey)): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
</head>
<body class="login-page">
    <form method="post" class="login-box" id="loginForm">
        <h1>Prince Art Packages</h1>
        <p class="subtitle">Admin Dashboard Security</p>
        
        <?php if ($error): ?>
            <p class="error"><i class="ri-error-warning-fill"></i> <?= h($error) ?></p>
        <?php endif; ?>

        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <label>Username
            <input type="text" name="username" value="<?= h($_POST['username'] ?? '') ?>" required autofocus autocomplete="username">
        </label>
        <label>Password
            <input type="password" name="password" required autocomplete="current-password">
        </label>

        <?php if ($captchaType === 'turnstile'): ?>
            <!-- Cloudflare Turnstile Container -->
            <div id="turnstile-box" style="margin: 1.25rem 0 1rem; display: flex; justify-content: center; min-height: 65px;">
                <div class="cf-turnstile" data-sitekey="<?= h($turnstileSiteKey) ?>" data-theme="light"></div>
            </div>
            
            <!-- Fallback interactive widget if Cloudflare script is blocked or delayed -->
            <div id="fallback-robot-box" style="display: none;">
                <div class="cf-captcha-box" onclick="toggleRobotCheck(this)" style="cursor: pointer;">
                    <div class="cf-captcha-left">
                        <div class="cf-captcha-check" id="robotCheckSquare">
                            <svg id="robotCheckSvg" style="display:none; width: 18px; height: 18px; fill: none; stroke: #fff; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <span class="cf-captcha-label">I'm not a robot</span>
                    </div>
                    <div class="cf-captcha-brand">
                        <svg class="cf-captcha-logo" viewBox="0 0 48 48" fill="none">
                            <path d="M24 4C12.95 4 4 12.95 4 24s8.95 20 20 20 20-8.95 20-20S35.05 4 24 4z" fill="#0284c7" fill-opacity="0.1"/>
                            <path d="M34 24c0 5.52-4.48 10-10 10s-10-4.48-10-10 4.48-10 10-10v4l6-6-6-6v4c-7.73 0-14 6.27-14 14s6.27 14 14 14 14-6.27 14-14h-4z" fill="#0284c7"/>
                        </svg>
                        <span class="cf-captcha-title">Cloudflare</span>
                        <span class="cf-captcha-legal">Privacy - Terms</span>
                    </div>
                </div>
            </div>
            <input type="hidden" name="built_in_robot_check" id="builtInRobotCheck" value="">

            <script>
            // If Cloudflare widget doesn't render within 2.5 seconds (e.g. offline/blocked), reveal interactive fallback
            setTimeout(function() {
                var cfIframe = document.querySelector('#turnstile-box iframe');
                if (!cfIframe) {
                    var fb = document.getElementById('fallback-robot-box');
                    if (fb) fb.style.display = 'block';
                }
            }, 2500);

            function toggleRobotCheck(box) {
                var check = document.getElementById('robotCheckSquare');
                var svg = document.getElementById('robotCheckSvg');
                var input = document.getElementById('builtInRobotCheck');
                if (check.classList.contains('checked') || check.classList.contains('loading')) return;
                
                check.classList.add('loading');
                setTimeout(function() {
                    check.classList.remove('loading');
                    check.classList.add('checked');
                    svg.style.display = 'block';
                    input.value = "<?= h($challengeToken) ?>";
                }, 600);
            }
            </script>

        <?php elseif ($captchaType === 'recaptcha' && !empty($recaptchaSiteKey)): ?>
            <!-- Google reCAPTCHA Container -->
            <div style="margin: 1.25rem 0 1rem; display: flex; justify-content: center;">
                <div class="g-recaptcha" data-sitekey="<?= h($recaptchaSiteKey) ?>"></div>
            </div>
            <input type="hidden" name="built_in_robot_check" id="builtInRobotCheck" value="">

        <?php elseif ($captchaType === 'built_in' || ($captchaType === 'recaptcha' && empty($recaptchaSiteKey))): ?>
            <!-- Standalone Interactive "I'm not a robot" Checkbox (Matching User Screenshot) -->
            <div class="cf-captcha-box" onclick="toggleRobotCheck(this)" style="cursor: pointer;">
                <div class="cf-captcha-left">
                    <div class="cf-captcha-check" id="robotCheckSquare">
                        <svg id="robotCheckSvg" style="display:none; width: 18px; height: 18px; fill: none; stroke: #fff; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <span class="cf-captcha-label">I'm not a robot</span>
                </div>
                <div class="cf-captcha-brand">
                    <svg class="cf-captcha-logo" viewBox="0 0 48 48" fill="none">
                        <path d="M24 6c-9.94 0-18 8.06-18 18s8.06 18 18 18c8.84 0 16.2-6.38 17.7-14.75h-4.14C34.2 30.64 28.61 36 24 36c-6.63 0-12-5.37-12-12s5.37-12 12-12c3.31 0 6.32 1.34 8.49 3.51L27 21h15V6l-5.66 5.66C33.2 8.52 28.84 6 24 6z" fill="#4285F4"/>
                    </svg>
                    <span class="cf-captcha-title">reCAPTCHA</span>
                    <span class="cf-captcha-legal">Privacy - Terms</span>
                </div>
            </div>
            <input type="hidden" name="built_in_robot_check" id="builtInRobotCheck" value="">

            <script>
            function toggleRobotCheck(box) {
                var check = document.getElementById('robotCheckSquare');
                var svg = document.getElementById('robotCheckSvg');
                var input = document.getElementById('builtInRobotCheck');
                if (check.classList.contains('checked') || check.classList.contains('loading')) return;
                
                check.classList.add('loading');
                setTimeout(function() {
                    check.classList.remove('loading');
                    check.classList.add('checked');
                    svg.style.display = 'block';
                    input.value = "<?= h($challengeToken) ?>";
                }, 600);
            }
            </script>
        <?php endif; ?>

        <button type="submit" style="width: 100%; justify-content: center; margin-top: 0.5rem; padding: 0.85rem; font-size: 1rem;">
            <i class="ri-lock-line"></i> Log In
        </button>
    </form>
</body>
</html>
