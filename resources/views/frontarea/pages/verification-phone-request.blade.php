{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationSendRequest::class)->selector('#frontarea-verification-phone-request-form')->ignore('.skip-validation') !!}
@endpush

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <section class="auth-form">

                    {{ Form::open(['url' => route('frontarea.verification.phone.send'), 'id' => 'frontarea-verification-phone-request-form', 'role' => 'auth']) }}

                        <div class="centered"><strong>{{ trans('cortex/auth::common.account_verification_phone') }}</strong></div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <div class="input-group input-group-lg">
                                @if (auth()->guard(app('request.guard'))->user())
                                    {{ Form::tel('phone_input', old('phone', $currentUser->phone), ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus', 'disabled' => 'disabled']) }}
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

                        <div>
                            {{ Html::link(route('frontarea.login'), trans('cortex/auth::common.account_login')) }}
                            {{ trans('cortex/foundation::common.or') }}
                            {{ Html::link(route('frontarea.register.member'), trans('cortex/auth::common.account_register')) }}
                        </div>

                    {{ Form::close() }}

                </section>

            </div>

        </div>

    </div>

@endsection
