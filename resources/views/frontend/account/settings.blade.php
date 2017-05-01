{{-- Master Layout --}}
@extends('cortex/fort::frontend.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.settings') }}
@stop

@push('scripts')
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

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#settings" data-toggle="tab">{{ trans('cortex/fort::common.settings') }}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="active tab-pane" id="settings">

                                {{ Form::model($currentUser, ['url' => route('frontend.account.settings.update'), 'class' => 'form-horizontal']) }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    {{ Form::label('email', trans('cortex/fort::common.email'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                        @if ($currentUser->email_verified)
                                            <small class="text-success">{!! trans('cortex/fort::common.email_verified', ['date' => $currentUser->email_verified_at]) !!}</small>
                                        @else
                                            <small class="text-danger">{!! trans('cortex/fort::common.email_unverified', ['href' => route('frontend.verification.email.request')]) !!}</small>
                                        @endif

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                    {{ Form::label('username', trans('cortex/fort::common.username'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => $currentUser->username, 'required' => 'required']) }}

                                        @if ($errors->has('username'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>


                                <hr />


                                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    {{ Form::label('first_name', trans('cortex/fort::common.first_name'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => $currentUser->first_name ?: trans('cortex/fort::common.first_name')]) }}

                                        @if ($errors->has('first_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    {{ Form::label('last_name', trans('cortex/fort::common.last_name'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => $currentUser->last_name ?: trans('cortex/fort::common.last_name')]) }}

                                        @if ($errors->has('last_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <hr />

                                <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                    {{ Form::label('gender', trans('cortex/fort::common.gender'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::select('gender', ['male' => trans('cortex/fort::common.male'), 'female' => trans('cortex/fort::common.female')], null, ['class' => 'form-control select2', 'placeholder' => trans('common.select'), 'data-allow-clear' => true, 'data-minimum-results-for-search' => 'Infinity']) }}

                                        @if ($errors->has('gender'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('gender') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Birthday --}}
                                <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                    {{ Form::label('birthday', trans('cortex/fort::common.birthday'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                            {{ Form::text('birthday', null, ['class' => 'form-control datepicker']) }}
                                        </div>

                                        @if ($errors->has('birthday'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('birthday') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
                                    {{ Form::label('country_code', trans('cortex/fort::common.country'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::select('country_code', [], null, ['class' => 'form-control ', 'data-allow-clear' => true, 'placeholder' => trans('cortex/fort::common.select')]) }}

                                        @if ($errors->has('country_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('country_code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('language_code') ? ' has-error' : '' }}">
                                    {{ Form::label('language_code', trans('cortex/fort::common.language'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::select('language_code', $languages, null, ['class' => 'form-control ', 'data-allow-clear' => true, 'placeholder' => trans('cortex/fort::common.select')]) }}

                                        @if ($errors->has('language_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('language_code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    {{ Form::label('phone', trans('cortex/fort::common.phone'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::number('phone', null, ['class' => 'form-control', 'placeholder' => $currentUser->phone ?: trans('cortex/fort::common.phone')]) }}

                                        @if ($currentUser->phone_verified)
                                            <small class="text-success">{!! trans('cortex/fort::common.phone_verified', ['date' => $currentUser->phone_verified_at]) !!}</small>
                                        @else
                                            <small class="text-danger">{!! trans('cortex/fort::common.phone_unverified', ['href' => route('frontend.verification.phone.request')]) !!}</small>
                                        @endif

                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <hr />

                                <div class="form-group{{ $errors->has('twitter') ? ' has-error' : '' }}">
                                    {{ Form::label('twitter', trans('cortex/fort::common.twitter'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-twitter"></i>
                                            </div>

                                            {{ Form::text('twitter', null, ['class' => 'form-control datepicker']) }}
                                        </div>

                                        @if ($errors->has('twitter'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('twitter') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('facebook') ? ' has-error' : '' }}">
                                    {{ Form::label('facebook', trans('cortex/fort::common.facebook'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-facebook"></i>
                                            </div>

                                            {{ Form::text('facebook', null, ['class' => 'form-control datepicker']) }}
                                        </div>

                                        @if ($errors->has('facebook'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('facebook') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('linkedin') ? ' has-error' : '' }}">
                                    {{ Form::label('linkedin', trans('cortex/fort::common.linkedin'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-linkedin"></i>
                                            </div>

                                            {{ Form::text('linkedin', null, ['class' => 'form-control datepicker']) }}
                                        </div>

                                        @if ($errors->has('linkedin'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('linkedin') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('google_plus') ? ' has-error' : '' }}">
                                    {{ Form::label('google_plus', trans('cortex/fort::common.google_plus'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-google-plus"></i>
                                            </div>

                                            {{ Form::text('google_plus', null, ['class' => 'form-control datepicker']) }}
                                        </div>

                                        @if ($errors->has('google_plus'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('google_plus') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('skype') ? ' has-error' : '' }}">
                                    {{ Form::label('skype', trans('cortex/fort::common.skype'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-skype"></i>
                                            </div>

                                            {{ Form::text('skype', null, ['class' => 'form-control datepicker']) }}
                                        </div>

                                        @if ($errors->has('skype'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('skype') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
                                    {{ Form::label('website', trans('cortex/fort::common.website'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-website"></i>
                                            </div>

                                            {{ Form::text('website', null, ['class' => 'form-control datepicker']) }}
                                        </div>

                                        @if ($errors->has('website'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('website') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <hr />

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    {{ Form::label('password', trans('cortex/fort::common.password'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password')]) }}

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    {{ Form::label('password_confirmation', trans('cortex/fort::common.password_confirmation'), ['class' => 'col-md-2 control-label']) }}

                                    <div class="col-md-10">
                                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password_confirmation')]) }}

                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
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

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        {{ Form::button(trans('cortex/fort::common.reset'), ['class' => 'btn btn-default btn-flat', 'type' => 'reset']) }}
                                        {{ Form::button('<i class="fa fa-user"></i> '.trans('cortex/fort::common.update'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>
                                </div>

                                {{ Form::close() }}

                            </div>
                            <!-- /.tab-pane -->

                        </div>
                        <!-- /.tab-content -->

                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>

            </div>

        </section>

    </div>

@endsection
