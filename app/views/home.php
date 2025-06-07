<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title">Bienvenido, <?php echo htmlspecialchars($user['name']); ?></h2>
                <p class="card-text text-muted">Hora actual: <?php echo $currentTime; ?></p>
                
                <div class="alert alert-info mt-3">
                    <strong>Rol:</strong> <?php echo htmlspecialchars($user['role']); ?>
                </div>
                <a href="<?php echo APP_URL; ?>/dbtest" class="btn btn-primary mt-3">Probar conexión a la base de datos</a>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h3 class="card-title mb-0">Lista de Usuarios</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'primary' : 'secondary'; ?>">
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 