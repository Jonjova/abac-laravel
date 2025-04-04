<div class="alert alert-danger">
    <h5>Error al cargar permisos</h5>
    <p>{{ $error ?? 'Error desconocido' }}</p>
    <button class="btn btn-sm btn-warning" onclick="window.location.reload()">
        Recargar página
    </button>
</div>