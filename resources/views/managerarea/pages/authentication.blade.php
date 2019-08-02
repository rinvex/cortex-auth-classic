{{-- Master Layout --}}
@extends('cortex/foundation::managerarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Managerarea\AuthenticationRequest::class)->selector('#managerarea-login-form')->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('frontarea.home') }}"><b>{{ $currentTenant->name }}</b></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/auth::common.account_login') }}</p>

            {{ Form::open(['url' => route('managerarea.login.process'), 'id' => 'managerarea-login-form', 'role' => 'auth']) }}

                <div class="form-group has-feedback{{ $errors->has('loginfield') ? ' has-error' : '' }}">
                    {{ Form::text('loginfield', old('loginfield'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.loginfield'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                    @if ($errors->has('loginfield'))
                        <span class="help-block">{{ $errors->first('loginfield') }}</span>
                    @endif
                </div>

                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    {{ Form::password('password', ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.password'), 'required' => 'required']) }}

                    @if ($errors->has('password'))
                        <span class="help-block">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                {{ Form::button('<i class="fa fa-sign-in"></i> '.trans('cortex/auth::common.login'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

            {{ Form::close() }}

            {{ Html::link(route('managerarea.passwordreset.request'), trans('cortex/auth::common.passwordreset')) }}

        </div>

    </div>

@endsection
