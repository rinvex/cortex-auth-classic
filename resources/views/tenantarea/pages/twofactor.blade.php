{{-- Master Layout --}}
@extends('cortex/tenants::tenantarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.twofactor_authentication') }}
@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                @include('cortex/fort::tenantarea.partials.sidebar')
            </div>

            <div class="col-md-9">

                <div class="profile-content">

                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane active" id="security">

                            {{ Form::open(['url' => route('tenantarea.account.twofactor.totp.update'), 'id' => 'tenantarea-twofactor-totp-form']) }}

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
                                            <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('tenantarea.account.twofactor.totp.disable') }}" onclick="event.preventDefault(); var form = document.getElementById('tenantarea-twofactor-totp-form'); form.action = '{{ route('tenantarea.account.twofactor.totp.disable') }}'; form.submit();">{{ trans('cortex/fort::common.disable') }}</a>
                                            <a class="btn btn-default btn-flat btn-xs pull-right" style="margin-right: 10px" href="{{ route('tenantarea.account.twofactor.totp.enable') }}">{{ trans('cortex/fort::common.settings') }}</a>
                                        @else
                                            <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('tenantarea.account.twofactor.totp.enable') }}">{{ trans('cortex/fort::common.enable') }}</a>
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
                                        <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('tenantarea.account.twofactor.phone.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}" onclick="event.preventDefault(); var form = document.getElementById('tenantarea-twofactor-totp-form'); form.action = '{{ route('tenantarea.account.twofactor.phone.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}'; form.submit();">{{ trans('cortex/fort::common.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}</a>

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
