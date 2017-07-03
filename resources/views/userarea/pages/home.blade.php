{{-- Master Layout --}}
@extends('cortex/foundation::userarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.password_reset') }}
@stop

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <section class="panel panel-default">
                    <header class="panel-heading">{{ trans('cortex/fort::common.welcome') }}</header>

                    <div class="panel-body">
                        {!! trans('cortex/fort::common.welcome_body') !!}
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
