<!DOCTYPE html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña | ModuStack Visit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--bs-body-bg); }
        .reset-card { max-width: 400px; width: 100%; border-radius: 1rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15); }
        .logo { width: 80px; margin: 0 auto 1rem auto; display: block; }
        @media (max-width: 576px) { .reset-card { margin: 1rem; } }
    </style>
    <script>
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    </script>
</head>
<body>
    <div class="card reset-card p-4">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Logo" class="logo">
        <h3 class="text-center mb-3">Restablecer Contraseña</h3>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (empty($success)): ?>
        <form method="post" action="<?php echo APP_URL; ?>/resetpassword/update">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="8"
                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[!@#$%^&*()_+\\-=\\[\\]{};':\\\"\\|,.<>\\/?]).{8,}$"
                    title="Debe tener al menos 8 caracteres, mayúsculas, minúsculas, un número y un carácter especial.">
                <div class="form-text" id="passwordHelp"></div>
            </div>
            <div class="mb-3">
                <label for="password2" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" id="password2" name="password2" required minlength="8">
            </div>
            <button type="submit" class="btn btn-primary w-100">Restablecer</button>
            <div class="mt-3 text-center">
                <a href="<?php echo APP_URL; ?>/login">Volver al login</a>
            </div>
        </form>
        <?php endif; ?>
        <footer class="mt-4 text-center text-muted">
            &copy; <?php echo date('Y'); ?> ModuStack Visit
        </footer>
    </div>
    <script>
        document.getElementById('password').addEventListener('input', function() {
            const val = this.value;
            const help = document.getElementById('passwordHelp');
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]).{8,}$/;
            if (!regex.test(val)) {
                help.textContent = 'Contraseña insegura: mínimo 8 caracteres, mayúsculas, minúsculas, número y carácter especial.';
                help.className = 'form-text text-danger';
            } else {
                help.textContent = 'Contraseña segura.';
                help.className = 'form-text text-success';
            }
        });
    </script>
</body>
</html> 