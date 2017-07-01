{{-- Master Layout --}}
@extends('cortex/foundation::backend.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.backend') }} » {{ $phrase }} » {{ $resource->slug ?? $resource->username }}
@stop

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <section class="content-header">
            <h1>{{ $resource->slug ?? $resource->username }}</h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('backend.home') }}"><i class="fa fa-dashboard"></i> {{ trans('cortex/foundation::common.backend') }}</a></li>
                <li><a href="{{ route("backend.{$type}.index") }}">{{ $phrase }}</a></li>
                <li class="active">{{ $resource->slug ?? $resource->username }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li><a href="{{ route("backend.{$type}.edit", ['resource' => $resource]) }}">{{ trans('cortex/foundation::common.details') }}</a></li>
                    <li><a href="{{ route("backend.{$type}.logs", ['resource' => $resource]) }}">{{ trans('cortex/foundation::common.logs') }}</a></li>
                    <li class="active"><a href="" href="#activities-tab" data-toggle="tab">{{ trans('cortex/foundation::common.activities') }}</a></li>
                    <li style="float: right; padding: 5px"><select class="form-control dataTableBuilderLengthChanger" aria-controls="{{ $id }}-table"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select></li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="activities-tab">

                        {!! $dataTable->table(['class' => 'table table-striped responsive dataTableBuilder', 'id' => "{$id}"]) !!}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

