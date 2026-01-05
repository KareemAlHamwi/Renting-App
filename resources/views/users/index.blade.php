@extends('components.layout')

@section('content')
    <div class="page-header flex-between">
        <h1>Users Management</h1>
        <p class="muted">View, Verify and Block users</p>
    </div>

    <x-user-table-filters :users="$users"></x-user-table-filters>
    <x-user-table :users="$users"></x-user-table>
@endsection
