{{-- Master Layout --}}
@extends('cortex/fort::frontend.common.layout')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.verification_email_request') }}
@stop

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <section class="panel panel-default">
                    <header class="panel-heading">{{ trans('cortex/fort::common.verification_email_request') }}</header>

                    <div class="panel-body">
                        {{ Form::open(['url' => route('frontend.verification.email.send'), 'class' => 'form-horizontal']) }}

                            @include('cortex/fort::frontend.alerts.success')
                            @include('cortex/fort::frontend.alerts.warning')
                            @include('cortex/fort::frontend.alerts.error')

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                {{ Form::label('email', trans('cortex/fort::common.email'), ['class' => 'col-md-4 control-label']) }}

                                <div class="col-md-6">
                                    {{ Form::email('email', old('email', auth()->guest() ? '' : $currentUser->email), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    {{ Form::button(trans('cortex/fort::common.reset'), ['class' => 'btn btn-default', 'type' => 'reset']) }}
                                    {{ Form::button('<i class="fa fa-envelope"></i> '.trans('cortex/fort::common.verification_email_request'), ['class' => 'btn btn-primary', 'type' => 'submit']) }}
                                </div>
                            </div>

                        {{ Form::close() }}
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
