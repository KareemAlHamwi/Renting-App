@extends('components.layout')

@section('content')
<div class="page-header text-center">
        <div class="user-header">
            <h1 >User Details</h1>
        </div>
    </div>

    <x-user-card :user="$user" />

    <h2>User Properties</h2>
    <x-property-table :properties="$user->properties" tableId="userPropertiesTable" :links="false" />

    <h2>User Reservations</h2>
    <x-reservations-table :reservations="$user->reservations" tableId="userReservationsTable" />
@endsection
