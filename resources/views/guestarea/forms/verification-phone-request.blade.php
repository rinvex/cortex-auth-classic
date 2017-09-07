{{-- Master Layout --}}
@extends('cortex/foundation::guestarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.verification_phone_request') }}
@stop

{{-- Scripts --}}
@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Guestarea\PhoneVerificationSendProcessRequest::class)->selector('#guestarea-verification-phone-send') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('guestarea.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/fort::common.verification_phone_request') }}</p>

            {{ Form::open(['url' => route('guestarea.verification.phone.send'), 'id' => 'guestarea-verification-phone-send']) }}

                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                    <div class="input-group">
                        @if (auth()->user())
                            {{ Form::number('phone', old('phone', $currentUser->phone), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus', 'disabled' => 'disabled']) }}
                            {{ Form::hidden('phone', old('phone', $currentUser->phone)) }}
                        @else
                            {{ Form::number('phone', old('phone'), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                        @endif

                        <div class="input-group-btn" data-toggle="buttons">
                            <label for="sms" class="btn btn-default @if(! old('method') || old('method') === 'sms') active @endif">
                                <input id="sms" name="method" type="radio" value="sms" autocomplete="off" @if(! old('method') || old('method') == 'sms') checked @endif> {{ trans('cortex/fort::common.sms') }}
                            </label>
                            <label for="call" class="btn btn-default @if(old('method') === 'call') active @endif">
                                <input id="call" name="method" type="radio" value="call" autocomplete="off" @if(old('method') === 'call') checked @endif> {{ trans('cortex/fort::common.call') }}
                            </label>
                        </div>
                    </div>

                    @if ($errors->has('phone'))
                        <span class="help-block">{{ $errors->first('phone') }}</span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {{ Form::button('<i class="fa fa-phone"></i> '.trans('cortex/fort::common.verification_phone_request'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                    </div>
                    <!-- /.col -->
                </div>

            {{ Form::close() }}

        </div>
        <!-- /.login-box-body -->
    </div>

@endsection
