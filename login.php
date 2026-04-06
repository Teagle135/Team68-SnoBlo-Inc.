<?php
// login.php — Login page for SnoBlo Inc.

session_start();

// If already logged in, go straight to profile
if (isset($_SESSION['user_email'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'db.php';

$error   = '';
$success = '';

// Pick up any message passed via query string (e.g. after signup or logout)
if (isset($_GET['msg'])) {
    $success = htmlspecialchars($_GET['msg']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim(htmlspecialchars($_POST['email']    ?? ''));
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please enter your email and password.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';

    } else {
        $stmt = $conn->prepare(
            'SELECT id, email, password FROM Login WHERE email = ?'
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = 'No account found with that email address.';
        } else {
            $user = $result->fetch_assoc();

            // Verify the submitted password against the stored hash
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);   // protect against session fixation
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['logged_in']  = true;

                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Incorrect password. Please try again.';
            }
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
    <title>Log In — SnoBlo</title>
    <link rel="stylesheet" href="style.css">
    <style>
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
            max-width: 420px;
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
        .pw-wrapper input { flex: 1; }
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
        .form-msg {
            padding: 10px 14px;
            border-radius: 4px;
            font-size: 0.9rem;
            margin-bottom: 18px;
        }
        .form-msg.error   { background: #fde8e8; color: #900; border: 1px solid #f5c6c6; }
        .form-msg.success { background: #e5f7ec; color: #1a6b35; border: 1px solid #b2dfca; }
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
        <a class="brand" href="index.php">Snoblo Inc.</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#services">Services</a></li>
            <li><a href="index.php#contact">Contact</a></li>
        </ul>
        <div class="auth-actions">
            <a class="btn btn-signup" href="signup.php">Sign Up</a>
            <a class="btn btn-signin" href="login.php">Log In</a>
        </div>
    </nav>
</div>

<!-- ── Login Card ── -->
<div class="auth-container">
    <div class="auth-card">
        <h1>Welcome Back</h1>
        <p class="subtitle">Log in to your SnoBlo account.</p>

        <div id="client-msg" class="form-msg" style="display:none;"></div>

        <?php if ($error): ?>
            <div class="form-msg error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="form-msg success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" onsubmit="return validateLogin()">

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
                           placeholder="Your password" required>
                    <button type="button" class="toggle-btn"
                            onclick="togglePassword('password', this)">Show</button>
                </div>
            </div>

            <button type="submit" class="submit-btn">Log In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </div>
    </div>
</div>

<script src="script.js"></script>
<script>
    const msg = document.getElementById('client-msg');
    const obs = new MutationObserver(() => {
        msg.style.display = msg.textContent.trim() ? 'block' : 'none';
    });
    obs.observe(msg, { childList: true, characterData: true, subtree: true });
</script>

</body>
</html>
