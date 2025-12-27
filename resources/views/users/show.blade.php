@extends('components.layout')

@section('content')
    <div class="user-header">

        <h2 class="username">{{ $user->username }}</h2>
        <span class="badge {{ $user->role == 1 ? 'badge-admin' : 'badge-user' }}" style="font-size: 1.25rem;">
            {{ $user->role == 1 ? 'Admin' : 'User' }}
        </span>

    </div>

    <x-user-card :user="$user" />

@endsection
