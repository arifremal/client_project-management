<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .login-container { max-width: 400px; margin: 50px auto; padding: 20px; background: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background-color: #5cb85c; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #4cae4c; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Project Management System</h2>
        <form action="authenticate.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <?php if (isset($_GET['error'])): ?>
                <p class="error">Invalid username or password</p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>