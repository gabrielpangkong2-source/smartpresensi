<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AbsenKuy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #1a56db 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px 36px;
        }

        .login-brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-brand h2 {
            font-weight: 800;
            color: #1a56db;
            font-size: 28px;
            margin-bottom: 6px;
        }

        .login-brand p {
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
        }

        .form-label {
            font-weight: 600;
            font-size: 12.5px;
            color: #4b5563;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #d1d5db;
            font-size: 13.5px;
            padding: 11px 14px;
            font-weight: 500;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-control:focus {
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1);
        }

        .btn-login {
            background: #1a56db;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            font-size: 14px;
            width: 100%;
            color: #fff;
        }

        .btn-login:hover {
            background: #1e3a8a;
            color: #fff;
        }

        .alert {
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            border: none;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-brand">
            <h2>SMART PRESENSI</h2>

        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('auth/proses') ?>" method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-1"></i>Login
            </button>
        </form>
    </div>
</body>

</html>