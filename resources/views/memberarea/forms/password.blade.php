{{-- Master Layout --}}
@extends('cortex/foundation::memberarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.password') }}
@stop

@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Memberarea\AccountPasswordRequest::class)->selector('#memberarea-account-password-update') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">

            <div class="row">

                <div class="col-md-6 col-md-offset-3">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{ route('memberarea.account.settings') }}">{{ trans('cortex/fort::common.basic_info') }}</a></li>
                            <li class="active"><a href="{{ route('memberarea.account.password') }}">{{ trans('cortex/fort::common.password') }}</a></li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane active" id="password-tab">

                                {{ Form::model($currentUser, ['url' => route('memberarea.account.password.update'), 'class' => 'form-horizontal', 'id' => 'memberarea-account-password-update']) }}

                                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                                    {{ Form::label('password', trans('cortex/fort::common.password'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password')]) }}
                                        <span class="fa fa-key form-control-feedback"></span>

                                        @if ($errors->has('password'))
                                            <span class="help-block">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    {{ Form::label('password_confirmation', trans('cortex/fort::common.password_confirmation'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password_confirmation')]) }}
                                        <span class="fa fa-key form-control-feedback"></span>

                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        {{ Form::button('<i class="fa fa-key"></i> '.trans('cortex/fort::common.update_password'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>
                                </div>

                                {{ Form::close() }}

                            </div>

                        </div>
                        <!-- /.tab-content -->

                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>

            </div>

        </section>

    </div>

@endsection
