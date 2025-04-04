@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h2>Gestión de Permisos para {{ $user->name }}</h2>
                <div class="d-flex justify-content-between">
                    <p class="text-muted mb-0">ID: {{ $user->id }} | Email: {{ $user->email }}</p>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>

        {{-- @include('partials.alerts') --}}

        <div class="row">
            <!-- Sección de Roles y Resumen -->
            <div class="col-md-4">
                <!-- Tarjeta de Asignación de Roles -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-users-cog"></i> Asignación de Roles</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.roles.assign', $user) }}" method="POST">
                            @csrf
                            <!-- Dentro de tu list-group de roles -->
                            @foreach ($roles as $role)
                                <label class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mr-2"
                                            {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                        <strong>{{ $role->name }}</strong>
                                    </div>
                                    <div>
                                        <span class="badge badge-primary badge-pill mr-2">
                                            {{ $role->permissions_count }}
                                        </span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                            data-target="#editPermissionsModal" data-role-id="{{ $role->id }}"
                                            data-role-name="{{ $role->name }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </div>
                                </label>
                            @endforeach
                            <button type="submit" class="btn btn-info btn-block mt-3">
                                <i class="fas fa-save"></i> Actualizar Roles
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tarjeta de Resumen de Permisos -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0"><i class="fas fa-key"></i> Resumen de Permisos</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Permisos Directos</h6>
                            <span class="badge badge-primary">
                                {{ count($userDirectPermissions) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <h6>Permisos Heredados</h6>
                            <span class="badge badge-success">
                                {{ count($inheritedPermissions) }}
                            </span>
                        </div>
                        <div>
                            <h6>Total de Permisos</h6>
                            <span class="badge badge-dark">
                                {{ $totalPermissionsCount }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal para editar permisos del rol -->
            @include('users.modals.edit_permissions')
            <!-- Sección de Permisos Directos -->
            <div class="col-md-8">
                <!-- Card de Permisos Combinados -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-key"></i> Gestión Completa de Permisos</h4>
                        <small class="text-white-50">Permisos directos y heredados</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.permissions.assign', $user) }}" method="POST">
                            @csrf
                            <div class="row">
                                @foreach ($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                                    <div class="col-md-4">
                                        @foreach ($chunk as $permission)
                                            @php
                                                $isInherited = in_array($permission->id, $inheritedPermissions);
                                                $isDirect = in_array($permission->id, $userDirectPermissions);
                                            @endphp

                                            <div class="custom-control custom-checkbox mb-3">
                                                <!-- Checkbox principal -->
                                                <input type="checkbox" class="custom-control-input"
                                                    id="perm_{{ $permission->id }}"
                                                    name="{{ $isInherited ? 'inherited_permissions[]' : 'direct_permissions[]' }}"
                                                    value="{{ $permission->id }}"
                                                    {{ $isInherited || $isDirect ? 'checked' : '' }}
                                                    {{ $isInherited ? 'data-is-inherited="true"' : '' }}>

                                                <label class="custom-control-label" for="perm_{{ $permission->id }}">
                                                    <strong>{{ $permission->name }}</strong>

                                                    <!-- Badges informativos -->
                                                    @if ($isInherited)
                                                        <div class="mt-1">
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-link"></i> Heredado
                                                            </span>
                                                            @foreach ($user->roles as $role)
                                                                @if ($role->hasPermissionTo($permission->name))
                                                                    <span
                                                                        class="badge badge-info ml-1">{{ $role->name }}</span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @elseif($isDirect)
                                                        <span class="badge badge-primary ml-1">
                                                            <i class="fas fa-user-shield"></i> Directo
                                                        </span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-4">
                                <i class="fas fa-save"></i> Guardar Todos los Permisos
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Deshabilita la edición de permisos heredados (opcional)
            document.querySelectorAll('input[data-is-inherited="true"]').forEach(checkbox => {
                checkbox.addEventListener('click', function(e) {
                    if (this.checked) {
                        this.checked = true; // Mantiene marcados los heredados
                    }
                });
            });
        });

        $(document).ready(function() {
            // Para depuración
            // Manejar la apertura del modal
            $('#editPermissionsModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var roleId = button.data('role-id');
                var roleName = button.data('role-name');

                var modal = $(this);
                modal.find('.modal-title').text('Editar Permisos del Rol: ' + roleName);

                // Mostrar spinner de carga
                modal.find('.modal-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p>Cargando permisos...</p>
            </div>
        `);

                // Construir la URL correctamente
                var url =
                    '{{ route('roles.permissions.edit', ['user' => $user->id, 'role' => ':roleId']) }}'
                    .replace(':roleId', roleId);

                console.log('URL a cargar:', url); // Para depuración

                // Cargar el formulario via AJAX
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    success: function(data) {
                        modal.find('.modal-body').html(data);
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al cargar los permisos';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMsg +=
                                `<br><small>${response.message || xhr.statusText}</small>`;
                        } catch (e) {
                            errorMsg += `<br><small>${xhr.statusText} (${xhr.status})</small>`;
                        }

                        modal.find('.modal-body').html(`
                <div class="alert alert-danger">
                    ${errorMsg}
                    <button class="btn btn-sm btn-link mt-2" onclick="$(this).closest('.modal').modal('hide')">
                        Cerrar
                    </button>
                    <button class="btn btn-sm btn-warning mt-2" onclick="window.location.reload()">
                        Recargar página
                    </button>
                </div>
            `);
                    }
                });
            });

            // Resto del código para guardar...
            // Manejar el clic en el botón Guardar Cambios
            $(document).on('click', '#savePermissionsBtn', function() {
                var form = $('#permissionsForm');
                var url = form.attr('action');
                var formData = form.serialize();
                var modal = $('#editPermissionsModal');
                var btn = $(this);

                // Mostrar estado de carga en el botón
                btn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Guardando...
        `);

                // Enviar datos via AJAX
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Mostrar notificación de éxito
                        toastr.success('Permisos actualizados correctamente', 'Éxito', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 3000,
                            iconClass: 'toast-success bg-success'
                        });

                        // Cerrar el modal después de un breve retraso
                        setTimeout(function() {
                            modal.modal('hide');
                        }, 1000);

                        // Opcional: Recargar la página o actualizar la UI
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        var errorMsg = 'Error al guardar los permisos';
                        try {
                            var response = JSON.parse(xhr.responseText);
                            errorMsg = response.message || errorMsg;

                            // Mostrar errores de validación si existen
                            if (response.errors) {
                                errorMsg += '<ul>';
                                $.each(response.errors, function(key, value) {
                                    errorMsg += '<li>' + value + '</li>';
                                });
                                errorMsg += '</ul>';
                            }
                        } catch (e) {
                            errorMsg += '<br><small>' + xhr.statusText + '</small>';
                        }

                        toastr.error(errorMsg, 'Error', {
                            closeButton: true,
                            timeOut: 0,
                            extendedTimeOut: 0,
                            preventDuplicates: true
                        });
                    },
                    complete: function() {
                        // Restaurar el botón a su estado normal
                        btn.prop('disabled', false).text('Guardar Cambios');
                    }
                });
            });

            // Manejar el envío del formulario al presionar Enter
            $(document).on('keypress', '#permissionsForm input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#savePermissionsBtn').click();
                }
            });

        });
    </script>
@endsection
