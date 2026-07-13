<!DOCTYPE html> 
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin | Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at top, #111a2e, #070a12);
            color: white;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(14, 20, 38, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        }

        h3 {
            font-weight: 700;
            margin-bottom: 25px;
            color: #3b82f6;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 10px;
            padding: 12px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #3b82f6;
            color: white;
            box-shadow: none;
        }

        label {
            color: #8892b0;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .btn-login {
            background: #3b82f6;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            transition: 0.3s;
            margin-top: 15px;
        }

        .btn-login:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h3 class="text-center">Admin Login</h3>
        <form action="cek_login.php" method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@portfolio.com" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 btn-login">Masuk Sekarang</button>
        </form>
    </div>

</body>
</html>