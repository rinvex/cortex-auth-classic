{{-- Master Layout --}}
@extends('cortex/foundation::guestarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.password_reset_request') }}
@stop

{{-- Scripts --}}
@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Guestarea\PasswordResetProcessRequest::class)->selector('#guestarea-passwordreset-send') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('guestarea.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('cortex/fort::common.password_reset_request') }}</p>

            {{ Form::open(['url' => route('guestarea.passwordreset.send'), 'id' => 'guestarea-passwordreset-send']) }}

                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                    <span class="fa fa-envelope form-control-feedback"></span>

                    @if ($errors->has('email'))
                        <span class="help-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {{ Form::button('<i class="fa fa-envelope"></i> '.trans('cortex/fort::common.password_reset_request'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                    </div>
                    <!-- /.col -->
                </div>

            {{ Form::close() }}

        </div>
        <!-- /.login-box-body -->
    </div>

@endsection
