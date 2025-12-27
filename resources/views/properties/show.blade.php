@extends('components.layout')

@section('content')
<div class="page-header text-center">
        <div class="user-header">
            <h1 >Property Details</h1>
        </div>
    </div>

    <x-property-card :property="$property" />
    <x-user-card :user="$property->owner" showActions="{{ false }}" cardHeader="Landlord Card"></x-user-card>

    <h2>Property Reservations</h2>
    <x-reservations-table-filters />
    <x-reservations-table :reservations="$property->reservations" />
@endsection
