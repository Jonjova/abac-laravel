@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Add your content here -->
        <h1>{{ $user->name }}</h1>
        <p>{{ $user->email }}</p>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit</a>
        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>

    </div>
@endsection
