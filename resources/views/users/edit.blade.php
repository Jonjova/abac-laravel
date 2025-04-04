@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit User</h1>
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group mb-3">
                <label for="roles" class="form-label">Roles</label>
                <select name="roles[]" id="roles" class="selectpicker form-control" multiple
                    data-live-search="true"
                    data-style="btn-light"
                    data-width="100%"
                    title="Select roles...">
                    
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ collect(old('roles', $user->roles->pluck('id')->toArray()))->contains($role->id) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('roles') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script>
        // Función mejorada con manejo de errores
        function initializeSelectPicker() {
            // Verificación en cascada con timeouts alternativos
            const maxAttempts = 5;
            let attempts = 0;
            
            function tryInit() {
                attempts++;
                
                // 1. Verificar dependencias
                if (typeof $ === 'undefined' || typeof $.fn.modal === 'undefined') {
                    if (attempts < maxAttempts) {
                        return setTimeout(tryInit, 300);
                    }
                    return console.error('Dependencias no cargadas después de '+maxAttempts+' intentos');
                }
    
                // 2. Verificar bootstrap-select
                if (typeof $.fn.selectpicker === 'function') {
                    $.fn.selectpicker.Constructor.BootstrapVersion = '4';
                    $('.selectpicker').selectpicker({
                        liveSearch: true,
                        actionsBox: true,
                        dropupAuto: false,
                        size: 'auto'
                    });
                    console.debug('Selectpicker inicializado correctamente');
                } else {
                    // Carga dinámica con verificación
                    if (!document.querySelector('script[src*="bootstrap-select.min.js"]')) {
                        const script = document.createElement('script');
                        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js';
                        script.onload = function() {
                            $.fn.selectpicker.Constructor.BootstrapVersion = '4';
                            $('.selectpicker').selectpicker('refresh');
                        };
                        document.head.appendChild(script);
                        
                        // Cargar CSS dinámicamente si es necesario
                        if (!document.querySelector('link[href*="bootstrap-select.min.css"]')) {
                            const link = document.createElement('link');
                            link.rel = 'stylesheet';
                            link.href = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css';
                            document.head.appendChild(link);
                        }
                    }
                }
            }
            
            tryInit();
        }
    
        // Control de inicialización mejorado
        if (document.readyState === 'complete') {
            initializeSelectPicker();
        } else {
            document.addEventListener('DOMContentLoaded', initializeSelectPicker);
            $(document).ready(initializeSelectPicker); // Doble seguro
        }
    </script>
    
@endsection

