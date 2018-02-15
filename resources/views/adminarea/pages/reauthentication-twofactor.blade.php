{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.reauthentication') }}
@endsection

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('adminarea.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/fort::common.reauthentication') }}</p>

            {{ Form::open(['url' => route('adminarea.reauthentication.twofactor.process'), 'id' => 'adminarea-reauthentication-form', 'role' => 'auth']) }}

                <div class="form-group has-feedback{{ $errors->has('token') ? ' has-error' : '' }}">
                    {{ Form::text('token', null, ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.authentication_code'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                    @if ($errors->has('token'))
                        <span class="help-block">{{ $errors->first('token') }}</span>
                    @endif
                </div>

                {{ Form::button('<i class="fa fa-sign-in"></i> '.trans('cortex/fort::common.login'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

            {{ Form::close() }}

        </div>

    </div>

@endsection
