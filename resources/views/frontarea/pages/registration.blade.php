{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.register') }}
@stop

{{-- Scripts --}}
@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Frontarea\RegistrationProcessRequest::class)->selector('#frontarea-registration-process') !!}
@endpush

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <section class="auth-form">

                    {{ Form::open(['url' => route('frontarea.register.process'), 'id' => 'frontarea-registration-process', 'role' => 'auth']) }}

                        <div class="centered"><strong>{{ trans('cortex/fort::common.account_register') }}</strong></div>

                        <div class="form-group has-feedback{{ $errors->has('username') ? ' has-error' : '' }}">
                            {{ Form::text('username', old('username'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.username'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                            @if ($errors->has('username'))
                                <span class="help-block">{{ $errors->first('username') }}</span>
                            @endif
                        </div>

                        <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                            {{ Form::email('email', old('email'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required']) }}

                            @if ($errors->has('email'))
                                <span class="help-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                            {{ Form::password('password', ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.password'), 'required' => 'required']) }}

                            @if ($errors->has('password'))
                                <span class="help-block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            {{ Form::password('password_confirmation', ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.password_confirmation'), 'required' => 'required']) }}

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>

                        {{ Form::button('<i class="fa fa-user-plus"></i> '.trans('cortex/fort::common.register'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}

                        <div>
                            {{ Html::link(route('frontarea.login'), trans('cortex/fort::common.account_login')) }}
                            {{ trans('cortex/foundation::common.or') }}
                            {{ Html::link(route('frontarea.passwordreset.request'), trans('cortex/fort::common.password_reset')) }}
                        </div>

                    {{ Form::close() }}

                </section>

            </div>

        </div>

    </div>

@endsection
