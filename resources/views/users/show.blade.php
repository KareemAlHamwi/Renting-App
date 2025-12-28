@extends('components.layout')

@section('content')
<div class="page-header text-center">
        <div class="user-header">
            <h1 >User Details</h1>
        </div>
    </div>

    <x-user-card :user="$user" />

    <h2>User Properties</h2>
    <x-property-table-filters :properties="$user->properties" tableId="userPropertiesTable" />
    <x-property-table :properties="$user->properties" tableId="userPropertiesTable" />

    <h2>User Reservations</h2>
    <x-reservations-table-filters :reservations="$user->reservations" tableId="userReservationsTable" />
    <x-reservations-table :reservations="$user->reservations" tableId="userReservationsTable" />
@endsection
