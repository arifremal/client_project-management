<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: none;
        }
        .login-header {
            background: #ffffff;
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
        }
        .login-body {
            padding: 2rem;
            background: #ffffff;
        }
        .brand-logo {
            font-size: 2rem;
            font-weight: 700;
            color: #5e72e4;
            margin-bottom: 1rem;
        }
        .brand-logo i {
            margin-right: 10px;
        }
        .form-control {
            height: 45px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding-left: 15px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #5e72e4;
        }
        .btn-login {
            background: #5e72e4;
            border: none;
            height: 45px;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .btn-login:hover {
            background: #4a5acf;
        }
        .input-group-text {
            background: transparent;
            border-right: none;
        }
        .form-floating>.form-control:not(:placeholder-shown)~label {
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            color: #5e72e4;
        }
        .form-floating>label {
            padding-left: 35px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="login-header">
                        <div class="brand-logo">
                            <i class="fas fa-project-diagram"></i> ProjectHub
                        </div>
                        <h4 class="mb-0">Sign in to your account</h4>
                    </div>
                    <div class="card-body login-body">
                        <form action="authenticate.php" method="post">
                            <?php if (isset($_GET['error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i> Invalid username or password
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                    <label for="username"><i class="fas fa-user me-2"></i>Username</label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                </div>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login
                                </button>
                            </div>
                            
                            <div class="text-center">
                                <a href="#" class="text-decoration-none">Forgot password?</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center text-white mt-3">
                    <p class="mb-0">© <?= date('Y') ?> ProjectHub. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>