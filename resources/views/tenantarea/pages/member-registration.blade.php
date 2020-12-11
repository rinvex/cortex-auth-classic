{{-- Master Layout --}}
@extends('cortex/foundation::tenantarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Tenantarea\RegistrationProcessRequest::class)->selector('#tenantarea-cortex-auth-registration-form')->ignore('.skip-validation') !!}
@endpush

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <section class="auth-form">

                    {{ Form::open(['url' => route('tenantarea.cortex.auth.account.register.process'), 'id' => 'tenantarea-cortex-auth-registration-form', 'role' => 'auth']) }}

                        <div class="centered"><strong>{{ trans('cortex/auth::common.account_register') }}</strong></div>

                        <div class="form-group has-feedback{{ $errors->has('username') ? ' has-error' : '' }}">
                            {{ Form::text('username', old('username'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.username'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                            @if ($errors->has('username'))
                                <span class="help-block">{{ $errors->first('username') }}</span>
                            @endif
                        </div>

                        <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                            {{ Form::email('email', old('email'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}

                            @if ($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                            {{ Form::password('password', ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.password'), 'required' => 'required']) }}

                            @if ($errors->has('password'))
                                <span class="help-block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            {{ Form::password('password_confirmation', ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.password_confirmation'), 'required' => 'required']) }}

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>

                        {{ Form::button('<i class="fa fa-user-plus"></i> '.trans('cortex/auth::common.register'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

                        <div>
                            {{ Html::link(route('tenantarea.cortex.auth.account.login'), trans('cortex/auth::common.account_login')) }}
                            {{ trans('cortex/foundation::common.or') }}
                            {{ Html::link(route('tenantarea.cortex.auth.account.passwordreset.request'), trans('cortex/auth::common.passwordreset')) }}
                        </div>

                    {{ Form::close() }}

                </section>

            </div>

        </div>

    </div>

@endsection
