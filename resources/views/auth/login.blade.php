<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — TaskFlow Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh; margin: 0;
            background: #0f172a;
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        /* Animated background blobs */
        body::before, body::after {
            content: ''; position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: .35; pointer-events: none;
        }
        body::before {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #6366f1, transparent);
            top: -100px; left: -100px;
            animation: blob1 8s ease-in-out infinite alternate;
        }
        body::after {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #818cf8, transparent);
            bottom: -80px; right: -80px;
            animation: blob2 10s ease-in-out infinite alternate;
        }
        @keyframes blob1 { to { transform: translate(60px, 80px) scale(1.1); } }
        @keyframes blob2 { to { transform: translate(-50px, -60px) scale(1.15); } }

        .login-wrap {
            width: 100%; max-width: 420px; padding: 20px;
            position: relative; z-index: 1;
        }
        .login-logo {
            text-align: center; margin-bottom: 32px;
        }
        .login-logo .logo-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, #6366f1, #818cf8);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 26px; color: #fff; margin-bottom: 14px;
            box-shadow: 0 8px 24px rgba(99,102,241,.4);
        }
        .login-logo h1 { color: #f8fafc; font-size: 22px; font-weight: 700; margin: 0; }
        .login-logo p  { color: #64748b; font-size: 13px; margin: 4px 0 0; }

        .login-card {
            background: rgba(255,255,255,.04);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 24px 64px rgba(0,0,0,.4);
        }
        .login-card h2 { color: #f1f5f9; font-size: 18px; font-weight: 600; margin-bottom: 6px; }
        .login-card .subtitle { color: #64748b; font-size: 13px; margin-bottom: 24px; }

        .form-label { color: #94a3b8; font-size: 12.5px; font-weight: 500; margin-bottom: 6px; }
        .input-group-text {
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
            border-right: none; color: #64748b;
        }
        .form-control {
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
            border-left: none; color: #f1f5f9; font-size: 13.5px;
            padding: 10px 14px; border-radius: 0 8px 8px 0;
        }
        .form-control::placeholder { color: #475569; }
        .form-control:focus {
            background: rgba(255,255,255,.08); border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.2); color: #f1f5f9;
        }
        .input-group .input-group-text { border-radius: 8px 0 0 8px; }
        .form-check-input { background-color: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); }
        .form-check-label { color: #94a3b8; font-size: 13px; }

        .btn-login {
            background: linear-gradient(135deg, #6366f1, #818cf8);
            border: none; color: #fff; font-weight: 600; font-size: 14px;
            padding: 11px; border-radius: 10px; width: 100%;
            transition: opacity .2s, transform .2s;
            box-shadow: 0 4px 16px rgba(99,102,241,.4);
        }
        .btn-login:hover { opacity: .9; transform: translateY(-1px); color: #fff; }

        .demo-box {
            margin-top: 20px; padding: 12px 16px;
            background: rgba(99,102,241,.1); border: 1px solid rgba(99,102,241,.2);
            border-radius: 10px; color: #94a3b8; font-size: 12.5px;
        }
        .demo-box strong { color: #818cf8; }
        .alert-danger { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.2); color: #fca5a5; border-radius: 10px; font-size: 13px; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-logo">
        <div class="logo-icon"><i class="bi bi-check2-all"></i></div>
        <h1>TaskFlow Pro</h1>
        <p>Project & Task Management</p>
    </div>

    <div class="login-card">
        <h2>Welcome back</h2>
        <p class="subtitle">Sign in to your account to continue</p>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <div class="mb-4 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Keep me signed in</label>
            </div>
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="demo-box">
            <strong>Demo credentials:</strong><br>
            admin@admin.com &nbsp;/&nbsp; password
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
