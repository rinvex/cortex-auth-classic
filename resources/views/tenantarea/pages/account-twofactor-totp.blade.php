{{-- Master Layout --}}
@extends('cortex/foundation::tenantarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Tenantarea\AccountTwoFactorTotpProcessRequest::class)->selector('#tenantarea-twofactor-totp-form')->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                @include('cortex/auth::tenantarea.partials.sidebar')
            </div>

            <div class="col-md-9">

                <div class="profile-content">

                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane active" id="security">

                            {{ Form::open(['url' => route('tenantarea.account.twofactor.totp.update'), 'class' => 'form-horizontal', 'id' => 'tenantarea-twofactor-totp-form']) }}

                                <p class="text-justify">
                                    {!! trans('cortex/auth::twofactor.totp_apps') !!}
                                </p>


                                <hr />


                                <div class="row">

                                    <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                        <span class="fa fa-mobile" style="font-size: 8em"></span>
                                    </div>

                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        {!! trans('cortex/auth::twofactor.totp_apps_step1') !!}
                                    </div>

                                </div>

                                <hr />

                                <div class="row">

                                    <div class="col-md-4 col-sm-4 col-xs-4 text-center">
                                        <img src="{{ $qrCode }}" />
                                    </div>

                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        {!! trans('cortex/auth::twofactor.totp_apps_step2') !!}

                                        <a class="btn btn-default text-center" role="button" data-toggle="collapse" href="#collapseSecretKey" aria-expanded="false" aria-controls="collapseSecretKey">
                                            {{ trans('cortex/auth::twofactor.totp_apps_step2_button') }}
                                        </a>

                                        <div class="collapse" id="collapseSecretKey">
                                            <hr />
                                            <div class="well">

                                                <p class="small">{{ trans('cortex/auth::twofactor.totp_apps_step2_1') }}</p>
                                                <code>{{ $secret }}</code>
                                                <p class="small">{{ trans('cortex/auth::twofactor.totp_apps_step2_2') }}</p>

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
                                        {!! trans('cortex/auth::twofactor.totp_apps_step3') !!}

                                        <div class="form-group{{ $errors->has('token') ? ' has-error' : '' }}" style="margin-left: 0; margin-right: 0">
                                            {{ Form::text('token', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.authentication_code'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                            @if ($errors->has('token'))
                                                <span class="help-block">{{ $errors->first('token') }}</span>
                                            @endif
                                        </div>

                                    </div>

                                </div>

                                <br />

                                @if(Arr::get($twoFactor, 'totp.enabled'))
                                    <div class="row">
                                        <div class="form-group">

                                            <div class="col-md-10 col-md-offset-1">

                                                <div class="text-center">
                                                    <a class="btn btn-default text-center" role="button" data-toggle="collapse" href="#collapse2Example" aria-expanded="false" aria-controls="collapseSecretKey">
                                                        {{ trans('cortex/auth::twofactor.totp_backup_button', ['count' => count(Arr::get($twoFactor, 'totp.backup'))]) }}
                                                    </a>
                                                </div>

                                                <div class="collapse" id="collapse2Example">

                                                    <br />

                                                    @if(Arr::get($twoFactor, 'totp.backup'))
                                                        <div class="panel panel-primary">
                                                            <header class="panel-heading">
                                                                <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('tenantarea.account.twofactor.totp.backup') }}" onclick="event.preventDefault(); var form = document.getElementById('tenantarea-twofactor-totp-form'); form.action = '{{ route('tenantarea.account.twofactor.totp.backup') }}'; form.submit();">{{ trans('cortex/auth::twofactor.totp_backup_generate') }}</a>
                                                                <h3 class="panel-title">{{ trans('cortex/auth::twofactor.totp_backup_head') }}</h3>
                                                            </header>
                                                            <div class="panel-body">
                                                                {{ trans('cortex/auth::twofactor.totp_backup_body') }}
                                                                <div>

                                                                    {!! trans('cortex/auth::twofactor.totp_backup_notice', ['backup_at' => Carbon\Carbon::parse(Arr::get($twoFactor, 'totp.backup_at'))->format('F d, Y - h:ia')]) !!}

                                                                    <ul class="list-group">
                                                                        @foreach(Arr::get($twoFactor, 'totp.backup') as $backup)
                                                                            <li class="list-group-item col-xs-6">{{ $backup }}</li>
                                                                        @endforeach
                                                                    </ul>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{ trans('cortex/auth::twofactor.totp_backup_none') }}
                                                    @endif

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                @endif

                                <div class="row">
                                    <div class="col-md-12 text-center profile-buttons">
                                        {{ Form::button('<i class="fa fa-cog"></i> '.trans('cortex/auth::twofactor.configure'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
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
