@extends('components.layout')

@section('content')
    <div class="page-header text-center">
        <div class="user-header">
            <h2 class="username">{{ $property->title }}</h2>
        </div>
    </div>

    <div class="card">


        <div>
            <p>
                <strong>Description:</strong>
                <br>
                {{ $property->description }}
            </p>
        </div>

        <div class="user-data">
            <p>
                <strong>Address:</strong>
                {{ $property->address }}
            </p>

            <p>
                <strong>Status:</strong>
                @if ($property->verified_at)
                    <span class="status verified">Verified</span>
                @else
                    <span class="status pending">Pending</span>
                @endif
            </p>

            <p>
                <strong>Governorate:</strong>
                <span id="governorateName" data-gov-id="{{ $property->governorate_id }}">
                    {{ $property->governorate_id }}
                </span>
            </p>

            <p>
                <strong>Rent:</strong>
                {{ $property->rent }}
            </p>
        </div>

        <div class="photo-section">
            <div class="photo-item left">
                {{-- <img src="{{ asset('images/property.jpg') }}" alt="Property Image"> --}}
            </div>
        </div>

        <div class="card-footer">
            <div class="footer-left">
                <a href="{{ url('/reservations') }}" class="btn btn-secondary">Reservations</a>
            </div>

            <div class="footer-right">
                <a href="{{ url('/properties') }}" class="btn btn-secondary">Back</a>

                <form action="{{ url('/properties/' . $property->id . '/verify') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary verify-btn"
                        @if ($property->verified_at) disabled @endif>
                        {{ $property->verified_at ? 'Verified' : 'Verify' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const govEl = document.getElementById("governorateName");
            if (!govEl) return;

            const govId = govEl.dataset.govId;
            if (!govId) return;

            const GOVS_URL = "{{ url('/api/governorates') }}";

            try {
                const res = await fetch(GOVS_URL, {
                    headers: {
                        "Accept": "application/json"
                    }
                });
                if (!res.ok) return;

                const json = await res.json();
                const list = Array.isArray(json) ? json : (json.data || []);

                const found = list.find(g => String(g.id) === String(govId));
                const name = found?.name ?? found?.title ?? found?.governorate_name;

                if (name) govEl.textContent = name;
            } catch (e) {
                // leave as ID if API fails
            }
        });
    </script>
@endsection
