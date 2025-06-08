<!DOCTYPE html>
<html lang="es" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | ModuStack Visit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--bs-body-bg); }
        .register-card { max-width: 400px; width: 100%; border-radius: 1rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15); }
        .logo { width: 80px; margin: 0 auto 1rem auto; display: block; }
        @media (max-width: 576px) { .register-card { margin: 1rem; } }
    </style>
    <script>
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    </script>
</head>
<body>
    <div class="card register-card p-4">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Logo" class="logo">
        <h3 class="text-center mb-3">Registro de Usuario</h3>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo APP_URL; ?>/register">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($old['nombre'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required autocomplete="username" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required minlength="8" title="Debe tener al menos 8 caracteres, mayúsculas, minúsculas, un número y un carácter especial.">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="form-text" id="passwordHelp"></div>
            </div>
            <div class="mb-3">
                <label for="password2" class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password2" name="password2" required minlength="8">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="form-text" id="password2Help"></div>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="btnRegister">Registrarse</button>
            <div class="mt-3 text-center">
                <a href="<?php echo APP_URL; ?>/login">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </form>
        <footer class="mt-4 text-center text-muted">
            &copy; <?php echo date('Y'); ?> ModuStack Visit
        </footer>
    </div>
    <script>
        // Validación de contraseña en cliente
        let passwordValid = false;
        let passwordsMatch = false;

        document.getElementById('password').addEventListener('input', function() {
            const val = this.value;
            const help = document.getElementById('passwordHelp');
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]).{8,}$/;
            if (!regex.test(val)) {
                help.textContent = 'Contraseña insegura: mínimo 8 caracteres, mayúsculas, minúsculas, número y carácter especial.';
                help.className = 'form-text text-danger';
                passwordValid = false;
            } else {
                help.textContent = 'Contraseña segura.';
                help.className = 'form-text text-success';
                passwordValid = true;
            }
            validatePasswordsMatch();
            updateRegisterButton();
        });

        document.getElementById('password2').addEventListener('input', function() {
            validatePasswordsMatch();
            updateRegisterButton();
        });

        function validatePasswordsMatch() {
            const password = document.getElementById('password').value;
            const password2 = document.getElementById('password2').value;
            const help = document.getElementById('password2Help');
            
            if (password2 === '') {
                help.textContent = '';
                help.className = 'form-text';
                passwordsMatch = false;
                return;
            }

            if (password === password2) {
                help.textContent = 'Las contraseñas coinciden.';
                help.className = 'form-text text-success';
                passwordsMatch = true;
            } else {
                help.textContent = 'Las contraseñas no coinciden.';
                help.className = 'form-text text-danger';
                passwordsMatch = false;
            }
        }

        function updateRegisterButton() {
            const btn = document.getElementById('btnRegister');
            btn.disabled = !(passwordValid && passwordsMatch);
        }

        // Inicializar el estado del botón al cargar
        updateRegisterButton();

        // Evitar envío si hay errores en cliente
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!(passwordValid && passwordsMatch)) {
                e.preventDefault();
            }
        });

        // Función para mostrar/ocultar contraseña
        function togglePasswordVisibility(buttonId, inputId) {
            document.getElementById(buttonId).addEventListener('click', function() {
                const passwordInput = document.getElementById(inputId);
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
        }

        // Inicializar los botones de mostrar/ocultar contraseña
        togglePasswordVisibility('togglePassword', 'password');
        togglePasswordVisibility('togglePassword2', 'password2');
    </script>
</body>
</html> 
</html> 