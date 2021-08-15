{{-- Master Layout --}}
@extends('cortex/foundation::managerarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Managerarea\PhoneVerificationSendRequest::class)->selector('#managerarea-cortex-auth-verification-phone-request-form')->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('managerarea.home') }}"><b>{{ app('request.tenant')->name }}</b></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/auth::common.account_verification_phone') }}</p>

            {{ Form::open(['url' => route('managerarea.cortex.auth.account.verification.phone.send'), 'id' => 'managerarea-cortex-auth-verification-phone-request-form', 'role' => 'auth']) }}

                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                    <div class="input-group input-group-lg">
                        @if (request()->user())
                            {{ Form::tel('phone_input', old('phone', request()->user()->phone), ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus', 'disabled' => 'disabled']) }}
                        @else
                            {{ Form::tel('phone_input', old('phone'), ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                        @endif

                        <div class="input-group-btn" data-toggle="buttons">
                            <label for="sms" class="btn btn-default @if(! old('method') || old('method') === 'sms') active @endif">
                                <input style="margin: 0 !important;" id="sms" name="method" type="radio" value="sms" autocomplete="off" @if(! old('method') || old('method') == 'sms') checked @endif> {{ trans('cortex/auth::common.sms') }}
                            </label>
                            <label for="call" class="btn btn-default @if(old('method') === 'call') active @endif">
                                <input style="margin: 0 !important;" id="call" name="method" type="radio" value="call" autocomplete="off" @if(old('method') === 'call') checked @endif> {{ trans('cortex/auth::common.call') }}
                            </label>
                        </div>
                    </div>

                    <span class="help-block hide">{{ trans('cortex/foundation::messages.invalid_phone') }}</span>
                    @if ($errors->has('phone'))
                        <span class="help-block">{{ $errors->first('phone') }}</span>
                    @endif
                </div>

                {{ Form::button('<i class="fa fa-phone"></i> '.trans('cortex/auth::common.verification_phone_request'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

            {{ Form::close() }}

            {{ Html::link(route('managerarea.cortex.auth.account.login'), trans('cortex/auth::common.account_login')) }}

        </div>

    </div>

@endsection
