@extends('components.layout')

@section('content')
    <div class="page-header text-center">
        <div class="user-header">
            <h1 >Reservation Details</h1>
        </div>
    </div>

    <x-reservation-card :reservation="$reservation"></x-reservation-card>
    <x-user-card :user="$reservation->user" showActions="{{ false }}" cardHeader="Tenant Card"></x-user-card>
    <x-property-card :property="$reservation->property" showActions="{{ false }}" />
    <x-user-card :user="$reservation->property->owner" showActions="{{ false }}" cardHeader="Landlord Card"></x-user-card>
@endsection
