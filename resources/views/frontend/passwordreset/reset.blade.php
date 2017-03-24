{{-- Master Layout --}}
@extends('cortex/fort::frontend.common.layout')

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
                    <header class="panel-heading">{{ trans('cortex/fort::common.password_reset') }}</header>

                    <div class="panel-body">
                        {{ Form::open(['route' => 'frontend.passwordreset.process', 'class' => 'form-horizontal']) }}
                            {{ Form::hidden('token', old('token', $token)) }}

                            @include('cortex/fort::frontend.alerts.success')
                            @include('cortex/fort::frontend.alerts.warning')
                            @include('cortex/fort::frontend.alerts.error')

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                {{ Form::label('email', trans('cortex/fort::common.email'), ['class' => 'col-md-4 control-label']) }}

                                <div class="col-md-6">
                                    {{ Form::email('email', old('email', $email), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'readonly' => 'readonly']) }}

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                {{ Form::label('password', trans('cortex/fort::common.new_password'), ['class' => 'col-md-4 control-label']) }}

                                <div class="col-md-6">
                                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.new_password'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                {{ Form::label('password_confirmation', trans('cortex/fort::common.new_password_confirmation'), ['class' => 'col-md-4 control-label']) }}

                                <div class="col-md-6">
                                    {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.new_password_confirmation'), 'required' => 'required']) }}

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    {{ Form::button(trans('cortex/fort::common.reset'), ['class' => 'btn btn-default', 'type' => 'reset']) }}
                                    {{ Form::button('<i class="fa fa-refresh"></i> '.trans('cortex/fort::common.password_reset'), ['class' => 'btn btn-primary', 'type' => 'submit']) }}
                                </div>
                            </div>

                        {{ Form::close() }}
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
