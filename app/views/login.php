<!DOCTYPE html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | ModuStack Visit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bs-body-bg);
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
        .logo {
            width: 80px;
            margin: 0 auto 1rem auto;
            display: block;
        }
        @media (max-width: 576px) {
            .login-card { margin: 1rem; }
        }
    </style>
    <script>
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    </script>
</head>
<body>
    <div class="card login-card p-4">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Logo" class="logo">
        <h3 class="text-center mb-3">Iniciar Sesión</h3>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo APP_URL; ?>/login">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required autocomplete="username" value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required minlength="8" autocomplete="current-password" title="Debe tener al menos 8 caracteres, mayúsculas, minúsculas, un número y un carácter especial.">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="form-text" id="passwordHelp"></div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
        <div class="mt-3 text-center">
            <a href="<?php echo APP_URL; ?>/register">¿No tienes cuenta? Regístrate aquí</a><br>
            <a href="<?php echo APP_URL; ?>/forgotpassword">¿Olvidaste tu contraseña?</a>
        </div>
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

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
</body>
</html> 