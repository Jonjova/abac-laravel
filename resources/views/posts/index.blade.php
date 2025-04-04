@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Publicaciones</h1>
        @if (session('success'))
            <script>
            toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "1000",
            "iconClass": "toast-success bg-success text-white"
            };
            toastr.success("{{ session('success') }}", "Éxito");
            </script>
        @endif


        @can('create posts')
            <a href="{{ route('posts.create') }}" class="btn btn-primary mb-2">Crear Post</a>
        @endcan

        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Contenido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->content }}</td>
                        <td>
                            @can('edit posts')
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning">Editar</a>
                            @endcan

                            @can('delete posts')
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
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
