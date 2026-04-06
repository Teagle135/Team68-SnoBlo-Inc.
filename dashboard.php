<?php
// dashboard.php — Proof-of-login page for SnoBlo Inc.
// Shows the logged-in user's details; redirects to login if no session exists.

session_start();

// Guard: must be logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$userEmail = htmlspecialchars($_SESSION['user_email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — SnoBlo</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .profile-container {
            min-height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f6fb;
            padding: 40px 16px;
            box-sizing: border-box;
        }
        .profile-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 18px rgba(3,8,55,0.10);
            padding: 44px 40px;
            width: 100%;
            max-width: 480px;
            text-align: center;
        }
        .profile-card .avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background-color: rgb(3, 8, 55);
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .profile-card h1 {
            margin: 0 0 8px;
            color: rgb(3, 8, 55);
            font-size: 1.5rem;
        }
        .profile-card .user-email {
            font-size: 1rem;
            color: #444;
            margin-bottom: 32px;
            word-break: break-all;
        }
        .profile-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .profile-links a {
            display: block;
            padding: 11px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background-color 0.15s;
        }
        .link-home {
            background-color: rgb(3, 8, 55);
            color: #ffffff;
        }
        .link-home:hover { background-color: #060f72; }
        .link-logout {
            background-color: #ffffff;
            color: rgb(3, 8, 55);
            border: 1px solid rgb(3, 8, 55);
        }
        .link-logout:hover { background-color: #f0f2f8; }
        .status-badge {
            display: inline-block;
            background: #e5f7ec;
            color: #1a6b35;
            border: 1px solid #b2dfca;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 28px;
        }
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
            <!-- Swap auth buttons for logged-in state -->
            <a class="btn btn-signin" href="logout.php">Log Out</a>
        </div>
    </nav>
</div>

<!-- ── Profile Card ── -->
<div class="profile-container">
    <div class="profile-card">
        <!-- Avatar: first letter of the email -->
        <div class="avatar"><?= strtoupper(substr($userEmail, 0, 1)) ?></div>

        <div class="status-badge">✓ Logged In</div>

        <h1>Welcome!</h1>
        <p class="user-email"><?= $userEmail ?></p>

        <div class="profile-links">
            <a class="link-home" href="index.html">← Back to Home</a>
            <!-- Placeholder for future booking page -->
            <a class="link-home" href="#" style="background:#3a4a8a;">Book a Service</a>
            <a class="link-logout" href="logout.php">Log Out</a>
        </div>
    </div>
</div>

</body>
</html>
