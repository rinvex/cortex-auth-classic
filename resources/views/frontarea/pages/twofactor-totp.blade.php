{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::twofactor.configure') }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Frontarea\TwoFactorTotpProcessSettingsRequest::class)->selector('#frontarea-twofactor-totp-form') !!}
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
                        {!! Menu::render('frontarea.user.sidebar', 'user.sidebar', []) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-9">

                <div class="profile-content">

                    <!-- Tab panes -->
                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane active" id="security">

                            {{ Form::open(['url' => route('frontarea.account.twofactor.totp.update'), 'class' => 'form-horizontal', 'id' => 'frontarea-twofactor-totp-form']) }}

                                <p class="text-justify">
                                    {!! trans('cortex/fort::twofactor.totp_apps') !!}
                                </p>


                                <hr />


                                <div class="row">

                                    <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                        <span class="fa fa-mobile" style="font-size: 8em"></span>
                                    </div>

                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        {!! trans('cortex/fort::twofactor.totp_apps_step1') !!}
                                    </div>

                                </div>

                                <hr />

                                <div class="row">

                                    <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                        <img src="{{ $qrCode }}" />
                                    </div>

                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        {!! trans('cortex/fort::twofactor.totp_apps_step2') !!}

                                        <a class="btn btn-default text-center" role="button" data-toggle="collapse" href="#collapseSecretKey" aria-expanded="false" aria-controls="collapseSecretKey">
                                            {{ trans('cortex/fort::twofactor.totp_apps_step2_button') }}
                                        </a>

                                        <div class="collapse" id="collapseSecretKey">
                                            <hr />
                                            <div class="well">

                                                <p class="small">{{ trans('cortex/fort::twofactor.totp_apps_step2_1') }}</p>
                                                <code>{{ $secret }}</code>
                                                <p class="small">{{ trans('cortex/fort::twofactor.totp_apps_step2_2') }}</p>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <hr />

                                <div class="row">

                                    <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                        <span class="fa fa-lock fa-5x" style="font-size: 8em"></span>
                                    </div>

                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        {!! trans('cortex/fort::twofactor.totp_apps_step3') !!}

                                        <div class="form-group{{ $errors->has('token') ? ' has-error' : '' }}" style="margin-left: 0; margin-right: 0">
                                            {{ Form::text('token', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.authentication_code'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                            @if ($errors->has('token'))
                                                <span class="help-block">{{ $errors->first('token') }}</span>
                                            @endif
                                        </div>

                                    </div>

                                </div>

                                <hr />

                                @if(array_get($twoFactor, 'totp.enabled'))
                                    <div class="row">
                                        <div class="form-group">

                                            <div class="col-md-10 col-md-offset-1">

                                                <div class="text-center">
                                                    <a class="btn btn-default text-center" role="button" data-toggle="collapse" href="#collapse2Example" aria-expanded="false" aria-controls="collapseSecretKey">
                                                        {{ trans('cortex/fort::twofactor.totp_backup_button', ['count' => count(array_get($twoFactor, 'totp.backup'))]) }}
                                                    </a>
                                                </div>

                                                <div class="collapse" id="collapse2Example">

                                                    <hr />

                                                    @if(array_get($twoFactor, 'totp.backup'))
                                                        <div class="panel panel-primary">
                                                            <header class="panel-heading">
                                                                <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('frontarea.account.twofactor.totp.backup') }}" onclick="event.preventDefault(); var form = document.getElementById('frontarea-twofactor-totp-form'); form.action = '{{ route('frontarea.account.twofactor.totp.backup') }}'; form.submit();">{{ trans('cortex/fort::twofactor.totp_backup_generate') }}</a>
                                                                <h3 class="panel-title">{{ trans('cortex/fort::twofactor.totp_backup_head') }}</h3>
                                                            </header>
                                                            <div class="panel-body">
                                                                {{ trans('cortex/fort::twofactor.totp_backup_body') }}
                                                                <div>

                                                                    {!! trans('cortex/fort::twofactor.totp_backup_notice', ['backup_at' => array_get($twoFactor, 'totp.backup_at')]) !!}

                                                                    <ul class="list-group">
                                                                        @foreach(array_get($twoFactor, 'totp.backup') as $backup)
                                                                            <li class="list-group-item col-xs-6">{{ $backup }}</li>
                                                                        @endforeach
                                                                    </ul>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{ trans('cortex/fort::twofactor.totp_backup_none') }}
                                                    @endif

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <hr />

                                @endif

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        {{ Form::button('<i class="fa fa-cog"></i> '.trans('cortex/fort::twofactor.configure'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>
                                </div>

                            {{ Form::close() }}

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection