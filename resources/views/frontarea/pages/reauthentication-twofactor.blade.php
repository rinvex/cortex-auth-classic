{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <section class="auth-form">

                    {{ Form::open(['url' => route('frontarea.cortex.auth.account.reauthentication.twofactor.process'), 'id' => 'frontarea-reauthentication-form', 'role' => 'auth']) }}

                        <div class="centered"><strong>{{ trans('cortex/auth::common.reauthentication.twofactor') }}</strong></div>

                        <div class="form-group has-feedback{{ $errors->has('token') ? ' has-error' : '' }}">
                            {{ Form::text('token', null, ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.authentication_code'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                            @if ($errors->has('token'))
                                <span class="help-block">{{ $errors->first('token') }}</span>
                            @endif
                        </div>

                        {{ Form::button('<i class="fa fa-sign-in"></i> '.trans('cortex/auth::common.login'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

                    {{ Form::close() }}

                </section>

            </div>

        </div>

    </div>

@endsection
