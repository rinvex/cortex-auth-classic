{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.login') }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Adminarea\AuthenticationRequest::class)->selector('#adminarea-login-form') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('adminarea.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/fort::common.account_login') }}</p>

            {{ Form::open(['url' => route('adminarea.login.process'), 'id' => 'adminarea-login-form', 'role' => 'auth']) }}

                <div class="form-group has-feedback{{ $errors->has('loginfield') ? ' has-error' : '' }}">
                    {{ Form::text('loginfield', old('loginfield'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.loginfield'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                    @if ($errors->has('loginfield'))
                        <span class="help-block">{{ $errors->first('loginfield') }}</span>
                    @endif
                </div>

                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    {{ Form::password('password', ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.password'), 'required' => 'required']) }}

                    @if ($errors->has('password'))
                        <span class="help-block">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                {{ Form::button('<i class="fa fa-sign-in"></i> '.trans('cortex/fort::common.login'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

            {{ Form::close() }}

            {{ Html::link(route('adminarea.passwordreset.request'), trans('cortex/fort::common.password_reset')) }}

        </div>

    </div>

@endsection
