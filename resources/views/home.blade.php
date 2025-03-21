@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h1>Modulos </h1>
                    @can('viewAny posts')
                    <div class="card mt-4 col-6">
                        <div class="card-body">
                            <!-- Título clicable para colapsar/expandir -->
                            <a href="#permissionsList" class="text-primary font-weight-bold h5 text-decoration-none" data-toggle="collapse" aria-expanded="false" aria-controls="permissionsList">
                                <i class="bi bi-caret-right-fill"></i> Permisos del Usuario
                                <span class="badge badge-pill badge-info ml-2" style="vertical-align: super;">{{ $permissionsCount }}</span>
                            </a>
                    
                            <p class="text-muted mt-2">Tienes {{ $permissionsCount }} permisos asignados:</p>
                    
                            <!-- Lista de permisos colapsable -->
                            <div class="collapse" id="permissionsList">
                                <ul class="">
                                    @foreach($permissions as $permission)
                                        <li class="">
                                            {{ $permission->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
