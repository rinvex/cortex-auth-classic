{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.verify_phone') }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Adminarea\PhoneVerificationProcessRequest::class)->selector('#adminarea-verification-phone-token-form') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('adminarea.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/fort::common.account_verification_phone') }}</p>

            {{ Form::open(['url' => route('adminarea.verification.phone.process'), 'id' => 'adminarea-verification-phone-token-form', 'role' => 'auth']) }}

                <div class="form-group has-feedback{{ $errors->has('token') ? ' has-error' : '' }}">
                    {{ Form::hidden('phone', old('phone', request('phone'))) }}
                    {{ Form::text('token', null, ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.authentication_code'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                    @if ($errors->has('token'))
                        <span class="help-block">{{ $errors->first('token') }}</span>
                    @endif

                    @if (session()->get('cortex.fort.twofactor.phone'))
                        {!! trans('cortex/fort::twofactor.backup_phone', ['href' => route('adminarea.verification.phone.request')]) !!}
                    @elseif(session()->get('cortex.fort.twofactor.totp'))
                        {!! trans('cortex/fort::twofactor.backup_totp') !!}
                    @endif
                </div>

                {{ Form::button('<i class="fa fa-check"></i> '.trans('cortex/fort::common.verify_phone'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

            {{ Form::close() }}

            {{ Html::link(route('adminarea.login'), trans('cortex/fort::common.account_login')) }}

        </div>

    </div>

@endsection
