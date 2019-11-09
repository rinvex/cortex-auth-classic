{{-- Master Layout --}}
@extends('cortex/foundation::managerarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('frontarea.home') }}"><b>{{ $currentTenant->name }}</b></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/auth::common.reauthentication.twofactor') }}</p>

            {{ Form::open(['url' => route('managerarea.reauthentication.twofactor.process'), 'id' => 'managerarea-reauthentication-form', 'role' => 'auth']) }}

                <div class="form-group has-feedback{{ $errors->has('token') ? ' has-error' : '' }}">
                    {{ Form::text('token', null, ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.authentication_code'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                    @if ($errors->has('token'))
                        <span class="help-block">{{ $errors->first('token') }}</span>
                    @endif
                </div>

                {{ Form::button('<i class="fa fa-sign-in"></i> '.trans('cortex/auth::common.login'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

            {{ Form::close() }}

        </div>

    </div>

@endsection
