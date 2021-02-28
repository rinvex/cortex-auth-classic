{{-- Master Layout --}}
@extends('cortex/foundation::managerarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Managerarea\EmailVerificationSendRequest::class)->selector('#managerarea-cortex-auth-verification-email-request-form')->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('frontarea.home') }}"><b>{{ app('request.tenant')->name }}</b></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/auth::common.account_verification_email') }}</p>

            {{ Form::open(['url' => route('managerarea.cortex.auth.account.verification.email.send'), 'id' => 'managerarea-cortex-auth-verification-email-request-form', 'role' => 'auth']) }}

                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    {{ Form::email('email', old('email', request()->user()->email ?? ''), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                    @if ($errors->has('email'))
                        <span class="help-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                {{ Form::button('<i class="fa fa-envelope"></i> '.trans('cortex/auth::common.verification_email_request'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

            {{ Form::close() }}

            {{ Html::link(route('managerarea.cortex.auth.account.login'), trans('cortex/auth::common.account_login')) }}

        </div>

    </div>

@endsection
