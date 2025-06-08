<!DOCTYPE html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | ModuStack Visit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--bs-body-bg); }
        .forgot-card { max-width: 400px; width: 100%; border-radius: 1rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15); }
        .logo { width: 80px; margin: 0 auto 1rem auto; display: block; }
        @media (max-width: 576px) { .forgot-card { margin: 1rem; } }
    </style>
    <script>
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    </script>
</head>
<body>
    <div class="card forgot-card p-4">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Logo" class="logo">
        <h3 class="text-center mb-3">Recuperar Contraseña</h3>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo APP_URL; ?>/forgotpassword/send">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required autocomplete="username">
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar enlace</button>
            <div class="mt-3 text-center">
                <a href="<?php echo APP_URL; ?>/login">Volver al login</a>
            </div>
        </form>
        <footer class="mt-4 text-center text-muted">
            &copy; <?php echo date('Y'); ?> ModuStack Visit
        </footer>
    </div>
</body>
</html> 