{{-- Master Layout --}}
@extends('cortex/foundation::guestarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.verify_phone') }}
@stop

{{-- Scripts --}}
@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Guestarea\PhoneVerificationProcessRequest::class)->selector('#guestarea-verification-phone-process') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('guestarea.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/fort::common.verify_phone') }}</p>

            {{ Form::open(['url' => route('guestarea.verification.phone.process'), 'id' => 'guestarea-verification-phone-process']) }}

                <div class="form-group has-feedback{{ $errors->has('token') ? ' has-error' : '' }}">
                    {{ Form::text('token', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.authentication_code'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                    <span class="fa fa-phone form-control-feedback"></span>

                    @if ($errors->has('token'))
                        <span class="help-block">{{ $errors->first('token') }}</span>
                    @endif

                    {{ trans('cortex/fort::twofactor.backup_notice') }}<br />

                    @if ($phoneEnabled)
                        <strong>{!! trans('cortex/fort::twofactor.backup_sms', ['href' => route('guestarea.verification.phone.request')]) !!}</strong>
                    @else
                        <strong>{{ trans('cortex/fort::twofactor.backup_code') }}</strong>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {{ Form::button('<i class="fa fa-check"></i> '.trans('cortex/fort::common.verify_phone'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                    </div>
                    <!-- /.col -->
                </div>

            {{ Form::close() }}

        </div>
        <!-- /.login-box-body -->
    </div>

@endsection
