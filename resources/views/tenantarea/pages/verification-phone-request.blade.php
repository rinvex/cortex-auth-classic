{{-- Master Layout --}}
@extends('cortex/tenants::tenantarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.verification_phone_request') }}
@stop

{{-- Scripts --}}
@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Tenantarea\PhoneVerificationSendProcessRequest::class)->selector('#tenantarea-verification-phone-send') !!}
@endpush

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <section class="auth-form">

                    {{ Form::open(['url' => route('tenantarea.verification.phone.send'), 'id' => 'tenantarea-verification-phone-send', 'role' => 'auth']) }}

                        <div class="centered"><strong>{{ trans('cortex/fort::common.account_verification_phone') }}</strong></div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <div class="input-group input-group-lg">
                                @if (auth()->user())
                                    {{ Form::number('phone', old('phone', $currentUser->phone), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus', 'disabled' => 'disabled']) }}
                                    {{ Form::hidden('phone', old('phone', $currentUser->phone)) }}
                                @else
                                    {{ Form::number('phone', old('phone'), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                                @endif

                                <div class="input-group-btn" data-toggle="buttons">
                                    <label for="sms" class="btn btn-default @if(! old('method') || old('method') === 'sms') active @endif">
                                        <input style="margin: 0 !important;" id="sms" name="method" type="radio" value="sms" autocomplete="off" @if(! old('method') || old('method') == 'sms') checked @endif> {{ trans('cortex/fort::common.sms') }}
                                    </label>
                                    <label for="call" class="btn btn-default @if(old('method') === 'call') active @endif">
                                        <input style="margin: 0 !important;" id="call" name="method" type="radio" value="call" autocomplete="off" @if(old('method') === 'call') checked @endif> {{ trans('cortex/fort::common.call') }}
                                    </label>
                                </div>
                            </div>

                            @if ($errors->has('phone'))
                                <span class="help-block">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>

                        {{ Form::button('<i class="fa fa-phone"></i> '.trans('cortex/fort::common.verification_phone_request'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

                        <div>
                            {{ Html::link(route('tenantarea.login'), trans('cortex/fort::common.account_login')) }}
                            {{ trans('cortex/foundation::common.or') }}
                            {{ Html::link(route('tenantarea.register'), trans('cortex/fort::common.account_register')) }}
                        </div>

                    {{ Form::close() }}

                </section>

            </div>

        </div>

    </div>

@endsection
