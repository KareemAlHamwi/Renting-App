@extends('components.layout')

@section('content')
    <div class="page-header flex-between">
        <h1>Properties Management</h1>
        <p class="muted">View, Verify and Deactivate properties</p>
    </div>

    <x-property-table-filters :properties="$properties" tableId="propertiesTable" />
    <x-property-table :properties="$properties" tableId="propertiesTable" />
@endsection
