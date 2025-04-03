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

@section('scripts')
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

        $('#editPermissionsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var roleId = button.data('role-id');
            var roleName = button.data('role-name');

            var modal = $(this);
            modal.find('.modal-title').text('Editar Permisos del Rol: ' + roleName);

            // Construye la URL correctamente con ambos parámetros
            var url = '{{ route('roles.permissions.edit', ['user' => ':userId', 'role' => ':roleId']) }}'
                .replace(':userId', '{{ $user->id }}')
                .replace(':roleId', roleId);

            // Carga el formulario via AJAX
            $.get(url, function(data) {
                    modal.find('.modal-body').html(data);
                })
                .fail(function() {
                    modal.find('.modal-body').html(
                        '<div class="alert alert-danger">Error al cargar los permisos</div>'
                    );
                });
        });
    </script>
@endsection
@endsection
