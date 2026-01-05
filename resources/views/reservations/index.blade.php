@extends('components.layout')

@section('content')
    <div class="page-header flex-between">
        <h1>Reservations Management</h1>
        <p class="muted">View and Cancel reservations</p>
    </div>

    <x-reservations-table-filters :reservations="$reservations" tableId="reservationsTable" />
    <x-reservations-table :reservations="$reservations" tableId="reservationsTable" />
@endsection
