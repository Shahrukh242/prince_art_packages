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

            if (empty($cfToken)) {
                $captchaErrorMsg = 'Please complete the Cloudflare security verification before logging in.';
            } else {
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
                curl_close($ch);

                if ($resp) {
                    $json = json_decode($resp, true);
                    if (!empty($json['success'])) {
                        $captchaValid = true;
                    } else {
                        $captchaErrorMsg = 'Security verification failed or expired. Please check the verification box again.';
                    }
                } else {
                    $captchaErrorMsg = 'Unable to verify Cloudflare security challenge. Please check your internet connection and try again.';
                }
            }
        } elseif ($captchaType === 'recaptcha') {
            $gToken = trim($_POST['g-recaptcha-response'] ?? '');

            if (empty($gToken)) {
                $captchaErrorMsg = 'Please check "I\'m not a robot" before clicking Log In.';
            } else {
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
                        $captchaErrorMsg = 'Google reCAPTCHA verification failed. Please try again.';
                    }
                } else {
                    $captchaErrorMsg = 'Unable to verify reCAPTCHA response. Please try again.';
                }
            }
        } elseif ($captchaType === 'built_in') {
            $fallback = trim($_POST['built_in_robot_check'] ?? '');
            if (!empty($fallback) && hash_equals($challengeToken, $fallback)) {
                $captchaValid = true;
            } else {
                $captchaErrorMsg = 'Please click and check "I\'m not a robot" before logging in.';
            }
        }

        if (!$captchaValid) {
            $error = $captchaErrorMsg ?: 'Please complete the security check.';
        } elseif (attempt_login($_POST['username'] ?? '', $_POST['password'] ?? '')) {
            unset($_SESSION['robot_challenge_token']);
            header('Location: index');
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

        <?php elseif ($captchaType === 'recaptcha' && !empty($recaptchaSiteKey)): ?>
            <!-- Google reCAPTCHA Container -->
            <div style="margin: 1.25rem 0 1rem; display: flex; justify-content: center; min-height: 78px;">
                <div class="g-recaptcha" data-sitekey="<?= h($recaptchaSiteKey) ?>"></div>
            </div>

        <?php elseif ($captchaType === 'built_in' || ($captchaType === 'recaptcha' && empty($recaptchaSiteKey))): ?>
            <!-- Standalone Interactive "I'm not a robot" Checkbox -->
            <div class="cf-captcha-box" onclick="toggleRobotCheck(this)" style="cursor: pointer;" id="interactiveCaptchaBox">
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

        <div id="clientCaptchaWarning" style="display:none; color:#dc2626; background:#fef2f2; border:1px solid #fecaca; border-radius:6px; padding:0.6rem 0.85rem; font-size:0.85rem; margin-top:0.75rem; text-align:center;">
            <i class="ri-alert-line"></i> Please check the security verification box before logging in.
        </div>

        <button type="submit" style="width: 100%; justify-content: center; margin-top: 0.75rem; padding: 0.85rem; font-size: 1rem;">
            <i class="ri-lock-line"></i> Log In
        </button>
    </form>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        var captchaType = "<?= $captchaType ?>";
        var warn = document.getElementById('clientCaptchaWarning');
        if (warn) warn.style.display = 'none';

        if (captchaType === 'turnstile') {
            var cfResp = document.querySelector('[name="cf-turnstile-response"]');
            if (!cfResp || !cfResp.value) {
                e.preventDefault();
                if (warn) {
                    warn.textContent = 'Please complete the Cloudflare "I\'m not a robot" check before logging in.';
                    warn.style.display = 'block';
                }
                return false;
            }
        } else if (captchaType === 'recaptcha') {
            var gResp = document.querySelector('[name="g-recaptcha-response"]');
            if (!gResp || !gResp.value) {
                e.preventDefault();
                if (warn) {
                    warn.textContent = 'Please check "I\'m not a robot" before logging in.';
                    warn.style.display = 'block';
                }
                return false;
            }
        } else if (captchaType === 'built_in') {
            var bi = document.getElementById('builtInRobotCheck');
            if (!bi || !bi.value) {
                e.preventDefault();
                if (warn) {
                    warn.textContent = 'Please click and check "I\'m not a robot" before logging in.';
                    warn.style.display = 'block';
                }
                var box = document.getElementById('interactiveCaptchaBox');
                if (box) {
                    box.style.border = '2px solid #ef4444';
                    setTimeout(function(){ box.style.border = ''; }, 2000);
                }
                return false;
            }
        }
    });
    </script>
</body>
</html>
