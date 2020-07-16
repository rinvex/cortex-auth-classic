{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Frontarea\AccountPasswordRequest::class)->selector('#frontarea-account-password-form')->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                @include('cortex/auth::frontarea.partials.sidebar')
            </div>
            <div class="col-md-9">
                <div class="profile-content">

                    {{ Form::model(app('request.user'), ['url' => route('frontarea.account.password.update'), 'id' => 'frontarea-account-password-form']) }}

                        <div class="row">

                            <div class="col-md-12">

                                <div class="form-group has-feedback{{ $errors->has('old_password') ? ' has-error' : '' }}">
                                    {{ Form::label('old_password', trans('cortex/auth::common.old_password'), ['class' => 'control-label']) }}
                                    {{ Form::password('old_password', ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.old_password')]) }}

                                    @if ($errors->has('old_password'))
                                        <span class="help-block">{{ $errors->first('old_password') }}</span>
                                    @endif
                                </div>

                            </div>

                        </div>

                        <hr />

                        <div class="row">

                            <div class="col-md-12">

                                <div class="form-group has-feedback{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                    {{ Form::label('new_password', trans('cortex/auth::common.new_password'), ['class' => 'control-label']) }}
                                    {{ Form::password('new_password', ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.new_password')]) }}

                                    @if ($errors->has('new_password'))
                                        <span class="help-block">{{ $errors->first('new_password') }}</span>
                                    @endif
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-12">

                                <div class="form-group has-feedback{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                                    {{ Form::label('new_password_confirmation', trans('cortex/auth::common.new_password_confirmation'), ['class' => 'control-label']) }}
                                    {{ Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.new_password_confirmation')]) }}

                                    @if ($errors->has('new_password_confirmation'))
                                        <span class="help-block">{{ $errors->first('new_password_confirmation') }}</span>
                                    @endif
                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center profile-buttons">
                                {{ Form::button('<i class="fa fa-save"></i> '.trans('cortex/auth::common.update_password'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                            </div>
                        </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>

@endsection
