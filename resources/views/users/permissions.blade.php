@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestión de Permisos para {{ $user->name }}</h4>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.permissions.assign', $user->id) }}">
                        @csrf

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Permisos Directos</label>
                            <div class="col-md-9">
                                <div class="row">
                                    @foreach($permissions as $permission)
                                    <div class="col-md-4 mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" 
                                                   class="custom-control-input" 
                                                   id="perm_{{ $permission->id }}" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}"
                                                   {{ $user->hasDirectPermission($permission) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                @error('permissions')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Permisos
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Permisos Actuales</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Permiso</th>
                                            <th>Origen</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->getAllPermissions() as $permission)
                                        <tr>
                                            <td>
                                                @if($user->hasDirectPermission($permission))
                                                    <span class="badge badge-primary">Directo</span>
                                                @else
                                                    <span class="badge badge-secondary">Via Rol</span>
                                                @endif
                                            </td>
                                            <td>{{ $permission->name }}</td>
                                            <td>
                                                @if($user->hasDirectPermission($permission))
                                                    Asignado directamente
                                                @else
                                                    {{ implode(', ', $user->getRoleNames()->toArray()) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->hasDirectPermission($permission))
                                                <form action="{{ route('users.permissions.revoke', [$user->id, $permission->id]) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('¿Estás seguro de revocar este permiso?')">
                                                        <i class="fas fa-trash-alt"></i> Revocar
                                                    </button>
                                                </form>
                                                @else
                                                <span class="text-muted">Gestionar desde roles</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No hay permisos asignados</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Seleccionar/deseleccionar todos los permisos
        $('#select-all').click(function() {
            $('input[name="permissions[]"]').prop('checked', this.checked);
        });
    });
</script>
@endsection