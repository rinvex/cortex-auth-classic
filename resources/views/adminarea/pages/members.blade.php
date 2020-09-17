{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.pages.datatable-index')

{{-- Datatable Filters --}}
@section('datatable-filters')
    @include('cortex/auth::adminarea.partials.datatable-filters', ['resource' => 'members'])
@endsection

@push('inline-scripts')
    <script>
        window.countries = @json($countries);
    </script>
    <script>
        window.languages = @json($languages);
    </script>
@endpush
