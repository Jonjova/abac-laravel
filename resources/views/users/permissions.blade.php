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

        @include('partials.alerts')

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
                        <!-- Form para asignar -->
                        <form action="{{ route('users.permissions.assign', $user) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Botón para expandir/colapsar todos (opcional) -->
                                    <button class="btn btn-sm btn-secondary toggle-all-modules mb-3" data-action="expand">
                                        Expandir Todos
                                    </button>

                                    @php
                                        // Agrupar permisos por módulo (código existente)
                                        $groupedPermissions = [];
                                        foreach ($permissions as $permission) {
                                            $details = is_string($permission->details)
                                                ? json_decode($permission->details, true)
                                                : $permission->details ?? [];

                                            $moduleName =
                                                $details['module']['name'] ??
                                                (explode(' ', $permission->name, 2)[1] ?? 'Otros');
                                            $moduleIcon = $details['module']['icon'] ?? 'folder';
                                            $moduleColor = $details['module']['color'] ?? 'text-secondary';

                                            if (!isset($groupedPermissions[$moduleName])) {
                                                $groupedPermissions[$moduleName] = [
                                                    'icon' => $moduleIcon,
                                                    'color' => $moduleColor,
                                                    'permissions' => [],
                                                ];
                                            }

                                            $permissionNameParts = explode(' ', $permission->name, 2);
                                            $action = $permissionNameParts[0];

                                            $groupedPermissions[$moduleName]['permissions'][] = [
                                                'id' => $permission->id,
                                                'action' => $action,
                                                'name' => $permission->name,
                                                'isInherited' => in_array($permission->id, $inheritedPermissions),
                                                'isDirect' => in_array($permission->id, $userDirectPermissions),
                                                'details' => $details,
                                            ];
                                        }
                                    @endphp

                                    <div class="permissions-tree">
                                        @foreach ($groupedPermissions as $moduleName => $moduleData)
                                            <div class="card mb-3">
                                                <div class="card-header d-flex justify-content-between align-items-center module-header collapsed"
                                                    data-toggle="collapse" href="#module-{{ Str::slug($moduleName) }}"
                                                    role="button" aria-expanded="false">
                                                    <div>
                                                        <i
                                                            class="fas fa-{{ $moduleData['icon'] }} {{ $moduleData['color'] }} mr-2"></i>
                                                        <strong>{{ $moduleName }}</strong>
                                                    </div>
                                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                                </div>

                                                <div class="collapse" id="module-{{ Str::slug($moduleName) }}">
                                                    <div class="card-body">
                                                        <ul class="list-group list-group-flush">
                                                            @foreach ($moduleData['permissions'] as $permission)
                                                                <li
                                                                    class="list-group-item {{ $permission['isInherited'] ? 'inherited-permission-item' : '' }}">
                                                                    <div class="d-flex align-items-center">
                                                                        <!-- Checkbox y botón de edición juntos -->
                                                                        <div class="d-flex align-items-center mr-3"
                                                                            style="min-width: 45px;">
                                                                            <!-- Botón de edición (solo visible si no es heredado) -->
                                                                            @if (!$permission['isInherited'])
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-outline-primary py-0 px-2 mr-2"
                                                                                    data-toggle="modal"
                                                                                    data-target="#editPermissionModal"
                                                                                    data-permission-id="{{ $permission['id'] }}"
                                                                                    data-permission-name="{{ $permission['name'] }}"
                                                                                    data-permission-description="{{ $permission['details']['descriptions']['es'] ?? '' }}"
                                                                                    title="Editar permiso">
                                                                                    <i class="fas fa-edit fa-sm"></i>
                                                                                </button>
                                                                            @endif

                                                                            <!-- Checkbox (deshabilitado si es heredado) -->
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox"
                                                                                    class="custom-control-input"
                                                                                    id="perm_{{ $permission['id'] }}"
                                                                                    name="{{ $permission['isInherited'] ? 'inherited_permissions[]' : 'direct_permissions[]' }}"
                                                                                    value="{{ $permission['id'] }}"
                                                                                    {{ $permission['isInherited'] || $permission['isDirect'] ? 'checked' : '' }}
                                                                                    {{ $permission['isInherited'] ? 'disabled data-is-inherited="true"' : '' }}>
                                                                                <label class="custom-control-label"
                                                                                    for="perm_{{ $permission['id'] }}"></label>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Información del permiso -->
                                                                        <div class="flex-grow-1">
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center flex-wrap">
                                                                                <div class="permission-info">
                                                                                    <strong>{{ $permission['action'] }}</strong>
                                                                                    @if (isset($permission['details']['descriptions']['es']))
                                                                                        <small
                                                                                            class="text-muted d-block">{{ $permission['details']['descriptions']['es'] }}</small>
                                                                                    @endif
                                                                                </div>

                                                                                <div class="badges-container">
                                                                                    @if ($permission['isInherited'])
                                                                                        <span
                                                                                            class="badge badge-success mr-1 mb-1">
                                                                                            <i class="fas fa-link"></i>
                                                                                            Heredado
                                                                                        </span>
                                                                                        <div
                                                                                            class="d-inline-flex flex-wrap">
                                                                                            @foreach ($user->roles as $role)
                                                                                                @if ($role->hasPermissionTo($permission['name']))
                                                                                                    <span
                                                                                                        class="badge badge-info mb-1 mr-1">{{ $role->name }}</span>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </div>
                                                                                    @elseif($permission['isDirect'])
                                                                                        <span
                                                                                            class="badge badge-primary mb-1">
                                                                                            <i
                                                                                                class="fas fa-user-shield"></i>
                                                                                            Directo
                                                                                        </span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block mt-4">
                                <i class="fas fa-save"></i> Guardar Todos los Permisos
                            </button>
                        </form>

                        <!-- Modal para editar permisos y atributos -->
                        @include('users.modals.edit_atribute')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .permissions-tree .module-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .permissions-tree .module-header:hover {
            background-color: #f8f9fa;
        }

        .permissions-tree .toggle-icon {
            transition: transform 0.3s;
        }

        .permissions-tree .module-header.collapsed .toggle-icon {
            transform: rotate(-90deg);
        }

        .permissions-tree .list-group-item {
            border-left: none;
            border-right: none;
        }

        .permissions-tree .list-group-item:last-child {
            border-bottom: none;
        }

        /* Estilos para badges en móvil */
        .permissions-tree .badges-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .permissions-tree .badge {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        /* Estilo para permisos heredados */
        .permissions-tree .inherited-permission {
            opacity: 0.7;
        }

        /* Ajustes para móvil */
        @media (max-width: 768px) {
            .permissions-tree .permission-info {
                width: 100%;
            }

            .permissions-tree .badges-container {
                width: 100%;
            }
        }

        /* Estilos para el grupo checkbox + botón */
        .list-group-item .d-flex.align-items-center {
            gap: 10px;
        }

        /* Botón de edición compacto */
        .btn-outline-primary.py-0 {
            padding-top: 0;
            padding-bottom: 0;
            line-height: 1.5;
        }

        /* Ajustes para móvil */
        @media (max-width: 768px) {
            .list-group-item .d-flex.align-items-center {
                flex-wrap: wrap;
            }

            .list-group-item .d-flex.align-items-center>div:first-child {
                margin-bottom: 8px;
                min-width: 100%;
            }

            .badges-container {
                width: 100%;
            }
        }

        /* Estilo para items de permisos heredados */
        .inherited-permission-item {
            opacity: 0.7;
            background-color: #f8f9fa;
        }

        .inherited-permission-item .badge-success {
            opacity: 1;
        }

        /* Estilo para checkboxes deshabilitados */
        .custom-control-input:disabled~.custom-control-label::before {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        /* Mantener badges visibles aunque el item esté semi-transparente */
        .badge {
            opacity: 1 !important;
        }
    </style>
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
                            timeOut: 1000,
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

        $(document).ready(function() {
            // Manejar el estado de los iconos de toggle
            $('.module-header').on('click', function() {
                $(this).toggleClass('collapsed');
            });

            // Colapsar/expandir todos los módulos
            $('.toggle-all-modules').on('click', function(e) {
                e.preventDefault();
                const action = $(this).data('action');
                if (action === 'expand') {
                    $('.module-header').removeClass('collapsed');
                    $('.collapse').collapse('show');
                    $(this).data('action', 'collapse').text('Colapsar Todos');
                } else {
                    $('.module-header').addClass('collapsed');
                    $('.collapse').collapse('hide');
                    $(this).data('action', 'expand').text('Expandir Todos');
                }
            });

            // Manejar el modal de edición
            $('#editPermissionModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const permissionId = button.data('permission-id');
                const permissionName = button.data('permission-name');
                const permissionDescription = button.data('permission-description');

                const modal = $(this);
                modal.find('#edit_permission_id').val(permissionId);
                modal.find('#edit_permission_name').val(permissionName);
                modal.find('#edit_permission_description').val(permissionDescription);
            });

            // Guardar cambios
            $('#savePermissionChanges').on('click', function() {
                const formData = $('#editPermissionForm').serialize();

                // Ejemplo de AJAX (debes adaptarlo a tu backend)
                $.ajax({
                    url: '/permissions/update',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editPermissionModal').modal('hide');
                        toastr.success('Permiso actualizado correctamente');
                        // Actualizar la UI si es necesario
                    },
                    error: function(xhr) {
                        toastr.error('Error al actualizar el permiso');
                    }
                });
            });
        });
    </script>
@endsection
