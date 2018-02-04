{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.reauthentication') }}
@endsection

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <section class="auth-form">

                    {{ Form::open(['url' => route('frontarea.reauthentication.password.process'), 'id' => 'frontarea-reauthentication-form', 'role' => 'auth']) }}

                    <div class="centered"><strong>{{ trans('cortex/fort::common.reauthentication') }}</strong></div>


                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                        {{ Form::password('password', ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/fort::common.password'), 'required' => 'required']) }}

                        @if ($errors->has('password'))
                            <span class="help-block">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    {{ Form::button('<i class="fa fa-sign-in"></i> '.trans('cortex/fort::common.login'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}


                    {{ Form::close() }}

                </section>

            </div>

        </div>

    </div>

@endsection
