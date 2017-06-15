{{-- Master Layout --}}
@extends('cortex/foundation::frontend.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.settings') }}
@stop

@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Frontend\AccountSettingsRequest::class)->selector('#frontend-account-settings-update') !!}

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

                $("[name='country_code']").select2({
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

            {{ Form::model($currentUser, ['url' => route('frontend.account.settings.update'), 'class' => 'form-horizontal', 'id' => 'frontend-account-settings-update']) }}

                <div class="row">

                    <div class="col-md-6 col-md-offset-3">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#basic-tab" data-toggle="tab">{{ trans('cortex/fort::common.basic_info') }}</a></li>
                                <li><a href="#social-tab" data-toggle="tab">{{ trans('cortex/fort::common.social') }}</a></li>
                                <li><a href="#security-tab" data-toggle="tab">{{ trans('cortex/fort::common.security') }}</a></li>
                            </ul>

                            <div class="tab-content">

                                <div class="tab-pane active" id="basic-tab">

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        {{ Form::label('email', trans('cortex/fort::common.email'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                            @if ($currentUser->email_verified)
                                                <small class="text-success">{!! trans('cortex/fort::common.email_verified', ['date' => $currentUser->email_verified_at]) !!}</small>
                                            @elseif($currentUser->email)
                                                <small class="text-danger">{!! trans('cortex/fort::common.email_unverified', ['href' => route('frontend.verification.email.request')]) !!}</small>
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
                                            {{ Form::select('gender', ['male' => trans('cortex/fort::common.male'), 'female' => trans('cortex/fort::common.female')], null, ['class' => 'form-control select2', 'placeholder' => trans('common.select'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

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
                                            {{ Form::select('country_code', [], null, ['class' => 'form-control ', 'data-allow-clear' => 'true', 'placeholder' => trans('cortex/fort::common.select')]) }}

                                            @if ($errors->has('country_code'))
                                                <span class="help-block">{{ $errors->first('country_code') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('language_code') ? ' has-error' : '' }}">
                                        {{ Form::label('language_code', trans('cortex/fort::common.language'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::select('language_code', $languages, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

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
                                                <small class="text-danger">{!! trans('cortex/fort::common.phone_unverified', ['href' => route('frontend.verification.phone.request')]) !!}</small>
                                            @endif

                                            @if ($errors->has('phone'))
                                                <span class="help-block">{{ $errors->first('phone') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane" id="social-tab">

                                    <div class="form-group has-feedback{{ $errors->has('twitter') ? ' has-error' : '' }}">
                                        {{ Form::label('twitter', trans('cortex/fort::common.twitter'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('twitter', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.twitter')]) }}
                                            <span class="fa fa-twitter form-control-feedback"></span>

                                            @if ($errors->has('twitter'))
                                                <span class="help-block">{{ $errors->first('twitter') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback{{ $errors->has('facebook') ? ' has-error' : '' }}">
                                        {{ Form::label('facebook', trans('cortex/fort::common.facebook'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('facebook', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.facebook')]) }}
                                            <span class="fa fa-facebook form-control-feedback"></span>

                                            @if ($errors->has('facebook'))
                                                <span class="help-block">{{ $errors->first('facebook') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback{{ $errors->has('linkedin') ? ' has-error' : '' }}">
                                        {{ Form::label('linkedin', trans('cortex/fort::common.linkedin'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('linkedin', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.linkedin')]) }}
                                            <span class="fa fa-linkedin form-control-feedback"></span>

                                            @if ($errors->has('linkedin'))
                                                <span class="help-block">{{ $errors->first('linkedin') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback{{ $errors->has('google_plus') ? ' has-error' : '' }}">
                                        {{ Form::label('google_plus', trans('cortex/fort::common.google_plus'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('google_plus', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.google_plus')]) }}
                                            <span class="fa fa-google-plus form-control-feedback"></span>

                                            @if ($errors->has('google_plus'))
                                                <span class="help-block">{{ $errors->first('google_plus') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback{{ $errors->has('skype') ? ' has-error' : '' }}">
                                        {{ Form::label('skype', trans('cortex/fort::common.skype'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('skype', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.skype')]) }}
                                            <span class="fa fa-skype form-control-feedback"></span>

                                            @if ($errors->has('skype'))
                                                <span class="help-block">{{ $errors->first('skype') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback{{ $errors->has('website') ? ' has-error' : '' }}">
                                        {{ Form::label('website', trans('cortex/fort::common.website'), ['class' => 'col-md-2 control-label']) }}

                                        <div class="col-md-10">
                                            {{ Form::text('website', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.website')]) }}
                                            <span class="fa fa-globe form-control-feedback"></span>

                                            @if ($errors->has('website'))
                                                <span class="help-block">{{ $errors->first('website') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane" id="security-tab">

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
                                                                    <a class="btn btn-default btn-flat btn-xs pull-right" style="margin-left: 10px" href="{{ route('frontend.account.twofactor.totp.enable') }}">@if(array_get($twoFactor, 'totp.enabled')) {{ trans('cortex/fort::common.settings') }} @else {{ trans('cortex/fort::common.enable') }} @endif</a>
                                                                    @if(array_get($twoFactor, 'totp.enabled'))
                                                                        <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('frontend.account.twofactor.totp.disable') }}">{{ trans('cortex/fort::common.disable') }}</a>
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
                                                                    @if(array_get($twoFactor, 'phone.enabled'))
                                                                        <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('frontend.account.twofactor.phone.disable') }}">{{ trans('cortex/fort::common.disable') }}</a>
                                                                    @else
                                                                        <a class="btn btn-default btn-flat btn-xs pull-right" href="{{ route('frontend.account.twofactor.phone.enable') }}">{{ trans('cortex/fort::common.enable') }}</a>
                                                                    @endif
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

                                </div>

                                <!-- /.tab-pane -->

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        {{ Form::button(trans('cortex/fort::common.reset'), ['class' => 'btn btn-default btn-flat', 'type' => 'reset']) }}
                                        {{ Form::button('<i class="fa fa-user"></i> '.trans('cortex/fort::common.update'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>
                                </div>

                            </div>
                            <!-- /.tab-content -->

                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>

                </div>

            {{ Form::close() }}

        </section>

    </div>

@endsection
