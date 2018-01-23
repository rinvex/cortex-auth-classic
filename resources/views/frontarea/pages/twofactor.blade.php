{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.twofactor') }}
@endsection

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
                            <li><a href="{{ route('frontarea.account.settings') }}"><i class="fa fa-cogs"></i>{{ trans('cortex/fort::common.settings') }}</a></li>
                            <li><a href="{{ route('frontarea.account.sessions') }}"><i class="fa fa-list-alt"></i>{{ trans('cortex/fort::common.sessions') }}</a></li>
                            <li class="active"><a href="{{ route('frontarea.account.twofactor.index') }}"><i class="fa fa-lock"></i>{{ trans('cortex/fort::common.twofactor') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">

                <div class="profile-content">

                    <!-- Tab panes -->
                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane active" id="security">

                            {{ Form::model($currentUser, ['url' => route('frontarea.account.settings.update'), 'id' => 'frontarea-account-twofactor-update']) }}

                                <h3 class="centered">
                                    @if(array_get($twoFactor, 'totp.enabled') || array_get($twoFactor, 'phone.enabled'))
                                        {!! trans('cortex/fort::twofactor.active') !!}
                                    @else
                                        {!! trans('cortex/fort::twofactor.inactive') !!}
                                    @endif
                                </h3>

                                <p class="text-justify">{{ trans('cortex/fort::twofactor.notice') }}</p>

                                <div class="panel panel-primary">
                                    <header class="panel-heading">
                                        @if(! empty($twoFactor['totp']['enabled']))
                                            <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('frontarea.account.twofactor.totp.disable') }}" onclick="event.preventDefault(); var form = document.getElementById('frontarea-account-twofactor-update'); form.action = '{{ route('frontarea.account.twofactor.totp.disable') }}'; form.submit();">{{ trans('cortex/fort::common.disable') }}</a>
                                            <a class="btn btn-default btn-flat btn-xs pull-right" style="margin-right: 10px" href="{{ route('frontarea.account.twofactor.totp.enable') }}">{{ trans('cortex/fort::common.settings') }}</a>
                                        @else
                                            <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('frontarea.account.twofactor.totp.enable') }}">{{ trans('cortex/fort::common.enable') }}</a>
                                        @endif

                                        <h3 class="panel-title">
                                            {{ trans('cortex/fort::twofactor.totp_head') }}
                                        </h3>
                                    </header>
                                    <div class="panel-body">
                                        {!! trans('cortex/fort::twofactor.totp_body') !!}
                                    </div>
                                </div>

                                <div class="panel panel-primary">
                                    <header class="panel-heading">
                                        <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('frontarea.account.twofactor.phone.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}" onclick="event.preventDefault(); var form = document.getElementById('frontarea-account-twofactor-update'); form.action = '{{ route('frontarea.account.twofactor.phone.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}'; form.submit();">{{ trans('cortex/fort::common.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}</a>

                                        <h3 class="panel-title">
                                            {{ trans('cortex/fort::twofactor.phone_head') }}
                                        </h3>
                                    </header>
                                    <div class="panel-body">
                                        {{ trans('cortex/fort::twofactor.phone_body') }}
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
