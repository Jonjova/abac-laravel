@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Publicaciones</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <ul>
        @foreach ($posts as $post)
            <li>
                <h2>{{ $post->title }}</h2>
                <p>{{ $post->content }}</p>
            </li>
        @endforeach
    </ul>
</div>
@endsection