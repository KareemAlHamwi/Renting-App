@extends('components.layout')

@section('content')
    <div class="page-header text-center">
        <div class="user-header">
            <h2 class="username">{{ $property->title }}</h2>
        </div>
    </div>

    <x-property-card :property="$property" />

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
