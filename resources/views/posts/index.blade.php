@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Publicaciones</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    @can('create posts')
        <a href="{{ route('posts.create') }}" class="btn btn-primary mb-2">Crear Post</a>
    @endcan

    <table class="table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Contenido</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->content }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection