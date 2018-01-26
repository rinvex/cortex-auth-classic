{{-- Master Layout --}}
@extends('cortex/tenants::tenantarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.account_password') }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Tenantarea\AccountPasswordRequest::class)->selector('#tenantarea-account-password-form') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            {{ $currentUser->name ?: $currentUser->username }}
                        </div>
                        @if($currentUser->title)
                            <div class="profile-usertitle-job">
                                {{ $currentUser->title }}
                            </div>
                        @endif
                    </div>
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li><a href="{{ route('tenantarea.account.settings') }}"><i class="fa fa-cogs"></i>{{ trans('cortex/fort::common.settings') }}</a></li>
                            <li><a href="{{ route('tenantarea.account.attributes') }}"><i class="fa fa-leaf"></i>{{ trans('cortex/fort::common.attributes') }}</a></li>
                            <li><a href="{{ route('tenantarea.account.sessions') }}"><i class="fa fa-list-alt"></i>{{ trans('cortex/fort::common.sessions') }}</a></li>
                            <li class="active"><a href="{{ route('tenantarea.account.password') }}"><i class="fa fa-key"></i>{{ trans('cortex/fort::common.password') }}</a></li>
                            <li><a href="{{ route('tenantarea.account.twofactor.index') }}"><i class="fa fa-lock"></i>{{ trans('cortex/fort::common.twofactor') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="profile-content">

                    {{ Form::model($currentUser, ['url' => route('tenantarea.account.password.update'), 'id' => 'tenantarea-account-password-form']) }}

                        <div class="row">

                            <div class="col-md-12">

                                <div class="form-group has-feedback{{ $errors->has('old_password') ? ' has-error' : '' }}">
                                    {{ Form::label('old_password', trans('cortex/fort::common.old_password')) }}
                                    {{ Form::password('old_password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.old_password')]) }}

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
                                    {{ Form::label('new_password', trans('cortex/fort::common.new_password')) }}
                                    {{ Form::password('new_password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.new_password')]) }}

                                    @if ($errors->has('new_password'))
                                        <span class="help-block">{{ $errors->first('new_password') }}</span>
                                    @endif
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-12">

                                <div class="form-group has-feedback{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                                    {{ Form::label('new_password_confirmation', trans('cortex/fort::common.new_password_confirmation')) }}
                                    {{ Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.new_password_confirmation')]) }}

                                    @if ($errors->has('new_password_confirmation'))
                                        <span class="help-block">{{ $errors->first('new_password_confirmation') }}</span>
                                    @endif
                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center profile-buttons">
                                {{ Form::button('<i class="fa fa-save"></i> '.trans('cortex/fort::common.update_password'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                            </div>
                        </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>

@endsection
