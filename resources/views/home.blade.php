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
                    <h1>Modulos</h1>
                    @can('viewAny posts')
                        <div class="card mt-4">
                            <div class="card-body">
                                <a href="{{ route('posts.index') }}" class="text-primary font-weight-bold h5 text-decoration-none">Post</a>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
