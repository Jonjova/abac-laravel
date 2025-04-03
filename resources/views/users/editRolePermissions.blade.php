<div class="container-fluid">
    <h4>Editar Permisos para el Rol: {{ $role->name }}</h4>
    <p>Usuario: {{ $user->name }}</p>
    
    <form id="permissionsForm" action="{{ route('roles.permissions.update', ['user' => $user->id, 'role' => $role->id]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                <div class="col-md-4">
                    @foreach($chunk as $permission)
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" 
                                   id="perm_{{ $permission->id }}" 
                                   name="permissions[]" 
                                   value="{{ $permission->id }}"
                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="perm_{{ $permission->id }}">
                                {{ $permission->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar Cambios
        </button>
    </form>
</div>