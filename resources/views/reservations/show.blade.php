@extends('components.layout')

@section('content')
    <div class="page-header text-center">
        <div class="user-header">
            <h2 class="username">Reservation {{ $reservation->id }}</h2>
        </div>
    </div>

    <div>
        <h2>Tenant</h2>
        <x-user-card :user="$reservation->user"></x-user-card>
    </div>

    <div>
        <h2>Landlord Property</h2>
        <x-property-card :property="$reservation->property" />
    </div>

@endsection
