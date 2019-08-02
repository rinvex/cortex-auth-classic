{{-- Master Layout --}}
@extends('cortex/foundation::tenantarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Main Content --}}
@section('content')

    <div id="headerwrap">
        <div class="container">

            {{-- Main content --}}
            <div class="row">
                <div class="col-md-12">
                    <h1><i class="fa fa-dashboard"></i> {{ trans('cortex/foundation::common.profile_welcome') }}</h1>
                    <h3>{{ trans('cortex/foundation::common.profile_welcome_body') }}</h3>
                </div>

            </div>

        </div>
    </div>

@endsection
