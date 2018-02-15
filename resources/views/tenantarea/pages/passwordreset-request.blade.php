{{-- Master Layout --}}
@extends('cortex/foundation::tenantarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.password_reset_request') }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Tenantarea\PasswordResetProcessRequest::class)->selector('#tenantarea-passwordreset-request-form') !!}
@endpush

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <section class="auth-form">

                    {{ Form::open(['url' => route('tenantarea.passwordreset.send'), 'id' => 'tenantarea-passwordreset-request-form', 'role' => 'auth']) }}

                        <div class="centered"><strong>{{ trans('cortex/fort::common.account_reset_password') }}</strong></div>

                        <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                            {{ Form::email('email', old('email'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                            @if ($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        {{ Form::button('<i class="fa fa-envelope"></i> '.trans('cortex/fort::common.password_reset_request'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

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
