{{-- Master Layout --}}
@extends('cortex/foundation::frontend.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.password_reset') }}
@stop

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('frontend.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/fort::common.password_reset') }}</p>

            {{ Form::open(['url' => route('frontend.passwordreset.process')]) }}
            {{ Form::hidden('token', old('token', $token)) }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                {{ Form::email('email', old('email', $email), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'readonly' => 'readonly']) }}

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.new_password'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.new_password_confirmation'), 'required' => 'required']) }}

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    {{ Form::button(trans('cortex/fort::common.reset'), ['class' => 'btn btn-default btn-flat', 'type' => 'reset']) }}
                    {{ Form::button('<i class="fa fa-envelope"></i> '.trans('cortex/fort::common.password_reset'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                </div>
                <!-- /.col -->
            </div>
            {{ Form::close() }}

            {{ Html::link(route('frontend.auth.login'), trans('cortex/fort::common.login')) }}

        </div>
        <!-- /.login-box-body -->
    </div>

@endsection
