<?php
// signup.php — New-account registration page for SnoBlo Inc.

session_start();

// If already logged in, send straight to profile
if (isset($_SESSION['user_email'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Collect & sanitise inputs
    $email    = trim(htmlspecialchars($_POST['email']    ?? ''));
    $password = $_POST['password']         ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $phone    = trim(htmlspecialchars($_POST['phone']    ?? ''));

    // 2. Server-side validation
    if (!$email || !$password || !$confirm || !$phone) {
        $error = 'All fields are required.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';

    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';

    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';

    } else {
        // 3. Check whether the email already exists
        $stmt = $conn->prepare('SELECT id FROM Login WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'An account with that email already exists.';
        } else {
            // 4. Hash the password and insert the new user
            $hashed = password_hash($password, PASSWORD_BCRYPT);

            $insert = $conn->prepare(
                'INSERT INTO Login (email, password, phone) VALUES (?, ?, ?)'
            );
            $insert->bind_param('sss', $email, $hashed, $phone);

            if ($insert->execute()) {
                $success = 'Account created! You can now log in.';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up — SnoBlo</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ── Page-level layout ── */
        .auth-container {
            min-height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f6fb;
            padding: 40px 16px;
            box-sizing: border-box;
        }
        .auth-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 18px rgba(3,8,55,0.10);
            padding: 40px 36px;
            width: 100%;
            max-width: 440px;
        }
        .auth-card h1 {
            margin: 0 0 6px;
            color: rgb(3, 8, 55);
            font-size: 1.6rem;
        }
        .auth-card .subtitle {
            margin: 0 0 28px;
            color: #555;
            font-size: 0.9rem;
        }

        /* ── Form elements ── */
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.88rem;
            margin-bottom: 5px;
            color: rgb(3, 8, 55);
        }
        .form-group input {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #c8cfe0;
            border-radius: 4px;
            font-size: 0.95rem;
            box-sizing: border-box;
            transition: border-color 0.15s;
        }
        .form-group input:focus {
            outline: none;
            border-color: rgb(3, 8, 55);
        }
        .pw-wrapper {
            display: flex;
            gap: 8px;
        }
        .pw-wrapper input {
            flex: 1;
        }
        .toggle-btn {
            background: none;
            border: 1px solid #c8cfe0;
            border-radius: 4px;
            padding: 0 10px;
            cursor: pointer;
            font-size: 0.82rem;
            color: rgb(3, 8, 55);
            white-space: nowrap;
        }
        .toggle-btn:hover { background: #f0f2f8; }

        /* ── Submit button (reuses nav .btn look) ── */
        .submit-btn {
            width: 100%;
            padding: 11px;
            background-color: rgb(3, 8, 55);
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: background-color 0.15s;
        }
        .submit-btn:hover { background-color: #060f72; }

        /* ── Messages ── */
        .form-msg {
            padding: 10px 14px;
            border-radius: 4px;
            font-size: 0.9rem;
            margin-bottom: 18px;
        }
        .form-msg.error   { background: #fde8e8; color: #900; border: 1px solid #f5c6c6; }
        .form-msg.success { background: #e5f7ec; color: #1a6b35; border: 1px solid #b2dfca; }

        /* ── Link to login ── */
        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #555;
        }
        .auth-footer a {
            color: rgb(3, 8, 55);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<!-- ── Shared Nav Bar ── -->
<div id="nav-bar">
    <nav>
        <a class="brand" href="index.html">SnoBlo Inc.</a>
        <ul class="nav-links">
            <li><a href="index.html#calculator">Services</a></li>
            <li><a href="index.html">Home</a></li>
            <li><a href="index.html">Contact</a></li>
        </ul>
        <div class="auth-actions">
            <a class="btn btn-signup" href="signup.php">Sign Up</a>
            <a class="btn btn-signin" href="login.php">Log In</a>
        </div>
    </nav>
</div>

<!-- ── Sign Up Card ── -->
<div class="auth-container">
    <div class="auth-card">
        <h1>Create an Account</h1>
        <p class="subtitle">Sign up to book snow removal services.</p>

        <!-- Client-side validation message placeholder -->
        <div id="client-msg" class="form-msg" style="display:none;"></div>

        <?php if ($error): ?>
            <div class="form-msg error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="form-msg success">
                <?= $success ?>
                <br><a href="login.php">Go to Login →</a>
            </div>
        <?php else: ?>

        <form method="POST" action="signup.php" onsubmit="return validateSignup()">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="pw-wrapper">
                    <input type="password" id="password" name="password"
                           placeholder="At least 6 characters" required>
                    <button type="button" class="toggle-btn"
                            onclick="togglePassword('password', this)">Show</button>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="pw-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password"
                           placeholder="Re-enter password" required>
                    <button type="button" class="toggle-btn"
                            onclick="togglePassword('confirm_password', this)">Show</button>
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                       placeholder="e.g. 905-555-0100" required>
            </div>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>

        <?php endif; ?>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Log In</a>
        </div>
    </div>
</div>

<script src="script.js"></script>
<script>
    const msg = document.getElementById('client-msg');
    if (msg && msg.textContent.trim() !== '') {
        msg.style.display = 'block';
    }
    const obs = new MutationObserver(() => {
        msg.style.display = msg.textContent.trim() ? 'block' : 'none';
    });
    obs.observe(msg, { childList: true, characterData: true, subtree: true });
</script>

</body>
</html>
