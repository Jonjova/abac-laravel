<form id="permissionsForm" action="{{ route('roles.permissions.update', $role->id ?? 0) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        @foreach($permissions as $permission)
            <div class="col-md-4 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                           name="permissions[]" 
                           value="{{ $permission->id }}"
                           id="perm_{{ $permission->id }}"
                           {{ in_array($permission->id, $rolePermissions ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                        {{ $permission->name }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</form>