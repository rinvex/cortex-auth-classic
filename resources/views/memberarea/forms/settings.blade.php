{{-- Master Layout --}}
@extends('cortex/foundation::memberarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.settings') }}
@stop

@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Memberarea\AccountSettingsRequest::class)->selector('#memberarea-account-settings-update') !!}

    <script>
        (function($) {
            $(function() {
                var countries = [
                    @foreach($countries as $code => $country)
                        { id: '{{ $code }}', text: '{{ $country['name'] }}', emoji: '{{ $country['emoji'] }}' },
                    @endforeach
                ];

                function formatCountry (country) {
                    if (! country.id) {
                        return country.text;
                    }

                    var $country = $(
                        '<span style="padding-right: 10px">' + country.emoji + '</span>' +
                        '<span>' + country.text + '</span>'
                    );

                    return $country;
                };

                $("select[name='country_code']").select2({
                    placeholder: "Select a country",
                    templateSelection: formatCountry,
                    templateResult: formatCountry,
                    data: countries
                }).val('{{ $currentUser->country_code }}').trigger('change');

            });
        })(jQuery);
    </script>
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
                            <li class="active"><a href="{{ route('memberarea.account.settings') }}">{{ trans('cortex/fort::common.basic_info') }}</a></li>
                            <li><a href="{{ route('memberarea.account.password') }}">{{ trans('cortex/fort::common.password') }}</a></li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane active" id="basic-tab">

                                {{ Form::model($currentUser, ['url' => route('memberarea.account.settings.update'), 'class' => 'form-horizontal', 'id' => 'memberarea-account-settings-update']) }}

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        {{ Form::label('email', trans('cortex/fort::common.email'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                            @if ($currentUser->email_verified)
                                                <small class="text-success">{!! trans('cortex/fort::common.email_verified', ['date' => $currentUser->email_verified_at]) !!}</small>
                                            @elseif($currentUser->email)
                                                <small class="text-danger">{!! trans('cortex/fort::common.email_unverified', ['href' => route('guestarea.verification.email.request')]) !!}</small>
                                            @endif

                                            @if ($errors->has('email'))
                                                <span class="help-block">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                        {{ Form::label('username', trans('cortex/fort::common.username'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => $currentUser->username, 'required' => 'required']) }}

                                            @if ($errors->has('username'))
                                                <span class="help-block">{{ $errors->first('username') }}</span>
                                            @endif
                                        </div>
                                    </div>


                                    <hr />


                                    <div class="form-group{{ $errors->has('name_prefix') ? ' has-error' : '' }}">
                                        {{ Form::label('name_prefix', trans('cortex/fort::common.name_prefix'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('name_prefix', null, ['class' => 'form-control', 'placeholder' => $currentUser->name_prefix ?: trans('cortex/fort::common.name_prefix')]) }}

                                            @if ($errors->has('name_prefix'))
                                                <span class="help-block">{{ $errors->first('name_prefix') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                        {{ Form::label('first_name', trans('cortex/fort::common.first_name'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => $currentUser->first_name ?: trans('cortex/fort::common.first_name')]) }}

                                            @if ($errors->has('first_name'))
                                                <span class="help-block">{{ $errors->first('first_name') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
                                        {{ Form::label('middle_name', trans('cortex/fort::common.middle_name'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('middle_name', null, ['class' => 'form-control', 'placeholder' => $currentUser->middle_name ?: trans('cortex/fort::common.middle_name')]) }}

                                            @if ($errors->has('middle_name'))
                                                <span class="help-block">{{ $errors->first('middle_name') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                        {{ Form::label('last_name', trans('cortex/fort::common.last_name'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => $currentUser->last_name ?: trans('cortex/fort::common.last_name')]) }}

                                            @if ($errors->has('last_name'))
                                                <span class="help-block">{{ $errors->first('last_name') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('name_suffix') ? ' has-error' : '' }}">
                                        {{ Form::label('name_suffix', trans('cortex/fort::common.name_suffix'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('name_suffix', null, ['class' => 'form-control', 'placeholder' => $currentUser->name_suffix ?: trans('cortex/fort::common.name_suffix')]) }}

                                            @if ($errors->has('name_suffix'))
                                                <span class="help-block">{{ $errors->first('name_suffix') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
                                        {{ Form::label('job_title', trans('cortex/fort::common.job_title'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('job_title', null, ['class' => 'form-control', 'placeholder' => $currentUser->job_title ?: trans('cortex/fort::common.job_title')]) }}

                                            @if ($errors->has('job_title'))
                                                <span class="help-block">{{ $errors->first('job_title') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <hr />

                                    <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {{ Form::label('gender', trans('cortex/fort::common.gender'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::hidden('gender') }}
                                            {{ Form::hidden('gender', '') }}
                                            {{ Form::select('gender', $genders, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_gender'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                                            @if ($errors->has('gender'))
                                                <span class="help-block">{{ $errors->first('gender') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Birthday --}}
                                    <div class="form-group has-feedback{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                        {{ Form::label('birthday', trans('cortex/fort::common.birthday'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('birthday', null, ['class' => 'form-control datepicker', 'data-auto-update-input' => 'false']) }}
                                            <span class="fa fa-calendar form-control-feedback"></span>

                                            @if ($errors->has('birthday'))
                                                <span class="help-block">{{ $errors->first('birthday') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
                                        {{ Form::label('country_code', trans('cortex/fort::common.country'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::hidden('country_code', '') }}
                                            {{ Form::select('country_code', [], null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_country'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                            @if ($errors->has('country_code'))
                                                <span class="help-block">{{ $errors->first('country_code') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('language_code') ? ' has-error' : '' }}">
                                        {{ Form::label('language_code', trans('cortex/fort::common.language'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::hidden('language_code', '') }}
                                            {{ Form::select('language_code', $languages, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_language'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                            @if ($errors->has('language_code'))
                                                <span class="help-block">{{ $errors->first('language_code') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        {{ Form::label('phone', trans('cortex/fort::common.phone'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::number('phone', null, ['class' => 'form-control', 'placeholder' => $currentUser->phone ?: trans('cortex/fort::common.phone')]) }}
                                            <span class="fa fa-phone form-control-feedback"></span>

                                            @if ($currentUser->phone_verified)
                                                <small class="text-success">{!! trans('cortex/fort::common.phone_verified', ['date' => $currentUser->phone_verified_at]) !!}</small>
                                            @elseif($currentUser->phone)
                                                <small class="text-danger">{!! trans('cortex/fort::common.phone_unverified', ['href' => route('guestarea.verification.phone.request')]) !!}</small>
                                            @endif

                                            @if ($errors->has('phone'))
                                                <span class="help-block">{{ $errors->first('phone') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if(! empty(config('rinvex.fort.twofactor.providers')))

                                        <hr />

                                        <div class="row">
                                            <div class="form-group">

                                                <div class="col-md-12">

                                                    <div class="text-center">
                                                        <a class="btn btn-default btn-flat text-center" role="button" data-toggle="collapse" href="#collapseTwoFactor" aria-expanded="false" aria-controls="collapseTwoFactor">
                                                            @if(array_get($twoFactor, 'totp.enabled') || array_get($twoFactor, 'phone.enabled'))
                                                                {!! trans('cortex/fort::twofactor.active') !!}
                                                            @else
                                                                {!! trans('cortex/fort::twofactor.inactive') !!}
                                                            @endif
                                                        </a>
                                                    </div>

                                                    <div class="collapse col-md-10 col-md-offset-1" id="collapseTwoFactor">

                                                        <hr />
                                                        <p class="text-justify">{{ trans('cortex/fort::twofactor.notice') }}</p>
                                                        <hr />

                                                        @if(in_array('totp', config('rinvex.fort.twofactor.providers')))

                                                            <div class="panel panel-primary">
                                                                <header class="panel-heading">
                                                                    @if(! empty($twoFactor['totp']['enabled']))
                                                                        <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('memberarea.account.twofactor.totp.disable') }}" onclick="event.preventDefault(); var form = document.getElementById('memberarea-account-settings-update'); form.action = '{{ route('memberarea.account.twofactor.totp.disable') }}'; form.submit();">{{ trans('cortex/fort::common.disable') }}</a>
                                                                        <a class="btn btn-default btn-flat btn-xs pull-right" style="margin-right: 10px" href="{{ route('memberarea.account.twofactor.totp.enable') }}">{{ trans('cortex/fort::common.settings') }}</a>
                                                                    @else
                                                                        <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('memberarea.account.twofactor.totp.enable') }}">{{ trans('cortex/fort::common.enable') }}</a>
                                                                    @endif

                                                                    <h3 class="panel-title">
                                                                        {{ trans('cortex/fort::twofactor.totp_head') }}
                                                                    </h3>
                                                                </header>
                                                                <div class="panel-body">
                                                                    {!! trans('cortex/fort::twofactor.totp_body') !!}
                                                                </div>
                                                            </div>

                                                        @endif

                                                        @if(in_array('phone', config('rinvex.fort.twofactor.providers')))

                                                            <div class="panel panel-primary">
                                                                <header class="panel-heading">
                                                                    <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('memberarea.account.twofactor.phone.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}" onclick="event.preventDefault(); var form = document.getElementById('memberarea-account-settings-update'); form.action = '{{ route('memberarea.account.twofactor.phone.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}'; form.submit();">{{ trans('cortex/fort::common.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}</a>

                                                                    <h3 class="panel-title">
                                                                        {{ trans('cortex/fort::twofactor.phone_head') }}
                                                                    </h3>
                                                                </header>
                                                                <div class="panel-body">
                                                                    {{ trans('cortex/fort::twofactor.phone_body') }}
                                                                </div>
                                                            </div>

                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endif

                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            {{ Form::button('<i class="fa fa-user"></i> '.trans('cortex/fort::common.update_basic_info'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
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
