@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Usuarios</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @can('create users')
            <a href="{{ route('users.create') }}" class="btn btn-primary mb-2">Crear Usuario</a>
        @endcan

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                                <span class="badge badge-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @can('assignPermissions users')
                                <a href="{{ route('users.permissions', $user) }}" class="btn btn-info" title="Gestionar permisos">
                                    <i class="fas fa-key"></i> Permisos
                                </a>
                            @endcan

                            @can('edit users')
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary">Editar</a>
                            @endcan

                            @can('delete users')
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            @endcan

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
