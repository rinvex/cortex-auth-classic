{{-- Master Layout --}}
@extends('cortex/foundation::frontend.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.register') }}
@stop

{{-- Scripts --}}
@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Frontend\RegistrationProcessRequest::class) !!}

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endpush

{{-- Main Content --}}
@section('content')

    <div class="register-box">
        <div class="register-logo">
            <a href="{{ route('frontend.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <!-- /.register-logo -->
        <div class="register-box-body">
            <p class="register-box-msg">{{ trans('cortex/fort::common.register_new_account') }}</p>

            {{ Form::open(['url' => route('frontend.auth.register.process')]) }}

                <div class="form-group has-feedback{{ $errors->has('username') ? ' has-error' : '' }}">
                    {{ Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.username'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                    <span class="fa fa-font form-control-feedback"></span>

                    @if ($errors->has('username'))
                        <span class="help-block">{{ $errors->first('username') }}</span>
                    @endif
                </div>

                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                    <span class="fa fa-envelope form-control-feedback"></span>

                    @if ($errors->has('email'))
                        <span class="help-block">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password'), 'required' => 'required']) }}
                    <span class="fa fa-key form-control-feedback"></span>

                    @if ($errors->has('password'))
                        <span class="help-block">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password_confirmation'), 'required' => 'required']) }}
                    <span class="fa fa-key form-control-feedback"></span>

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-xs-8">
                        {{ Html::link(route('frontend.auth.login'), trans('cortex/fort::common.registered_already')) }}

                        {{--<div class="checkbox icheck">--}}
                        {{--<label for="terms_agreement">--}}
                        {{--<input id="terms_agreement" name="terms_agreement" type="checkbox" autocomplete="off" value="1" @if(old('terms_agreement')) checked @endif> {!! trans('cortex/fort::common.terms_agreement', ['href' => route('pages.terms')]) !!}--}}
                        {{--</label>--}}
                        {{--</div>--}}
                    </div>
                    <!-- /.col -->

                    <div class="col-xs-4">
                        {{ Form::button('<i class="fa fa-user-plus"></i> '.trans('cortex/fort::common.register'), ['class' => 'btn btn-primary btn-block btn-flat', 'type' => 'submit']) }}
                    </div>
                    <!-- /.col -->
                </div>

            {{ Form::close() }}

        {{--<div class="social-auth-links text-center">--}}
        {{--<p>- {{ trans('cortex/fort::common.or') }} -</p>--}}
        {{--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat disabled"><i class="fa fa-facebook"></i> {{ trans('cortex/fort::common.register_via_facebook') }}</a>--}}
        {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat disabled"><i class="fa fa-google-plus"></i> {{ trans('cortex/fort::common.register_via_gplus') }}</a>--}}
        {{--</div>--}}
        <!-- /.social-auth-links -->

        </div>
        <!-- /.register-box-body -->
    </div>

@endsection
