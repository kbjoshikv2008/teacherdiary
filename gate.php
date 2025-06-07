<?php
session_start();

// Server-side password validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correctPassword = "math123"; // Store this securely in production (e.g., environment variable)
    $inputPassword = $_POST['password'] ?? '';
    
    if ($inputPassword === $correctPassword) {
        $_SESSION['authenticated'] = true;
        $_SESSION['last_activity'] = time();
        header('Location: index.php');
        exit;
    } else {
        $error = "Incorrect password. Please try again.";
    }
}

// Redirect if already authenticated
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Teacher's Digital Diary</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: #2d3748;
            margin-bottom: 1.5rem;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            font-size: 1rem;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 80%);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .error {
            color: #e53e3e;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Teacher's Digital Diary</h1>
        <form method="POST">
            <input type="password" id="passwordInput" name="password" placeholder="Enter password" required>
            <button type="submit">Login</button>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>