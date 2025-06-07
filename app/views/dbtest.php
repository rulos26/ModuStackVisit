<div class="card mt-4">
    <div class="card-header">
        <h4>Prueba de conexión a la base de datos</h4>
    </div>
    <div class="card-body">
        <p class="fw-bold <?php echo ($status === 'Conexión exitosa a la base de datos.') ? 'text-success' : 'text-danger'; ?>">
            <?php echo $status; ?>
        </p>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <a href="<?php echo APP_URL; ?>" class="btn btn-secondary mt-3">Volver al inicio</a>
    </div>
</div> 