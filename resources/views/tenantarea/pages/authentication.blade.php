{{-- Master Layout --}}
@extends('cortex/tenants::tenantarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.login') }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Tenantarea\AuthenticationRequest::class)->selector('#tenantarea-login-form') !!}
@endpush

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <section class="auth-form">

                    {{ Form::open(['url' => route('tenantarea.login.process'), 'id' => 'tenantarea-login-form', 'role' => 'auth']) }}

                        <div class="centered"><strong>{{ trans('cortex/fort::common.account_login') }}</strong></div>

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

                        <div>
                            {{ Html::link(route('tenantarea.register'), trans('cortex/fort::common.account_register')) }}
                            {{ trans('cortex/foundation::common.or') }}
                            {{ Html::link(route('tenantarea.passwordreset.request'), trans('cortex/fort::common.password_reset')) }}
                        </div>

                    {{ Form::close() }}

                </section>

            </div>

        </div>

    </div>

@endsection
