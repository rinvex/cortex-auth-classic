{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.verification_email_request') }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Adminarea\EmailVerificationSendRequest::class)->selector('#adminarea-verification-email-request-form') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('adminarea.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/fort::common.account_verification_email') }}</p>

            {{ Form::open(['url' => route('adminarea.verification.email.send'), 'id' => 'adminarea-verification-email-request-form', 'role' => 'auth']) }}

                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    {{ Form::email('email', old('email', auth()->guard(request('guard'))->guest() ? '' : $currentUser->email), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                    @if ($errors->has('email'))
                        <span class="help-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                {{ Form::button('<i class="fa fa-envelope"></i> '.trans('cortex/fort::common.verification_email_request'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

            {{ Form::close() }}

            {{ Html::link(route('adminarea.login'), trans('cortex/fort::common.account_login')) }}

        </div>

    </div>

@endsection
