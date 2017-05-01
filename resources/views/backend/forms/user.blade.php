{{-- Master Layout --}}
@extends('cortex/foundation::backend.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.backend') }} » {{ trans('cortex/fort::common.users') }} » {{ $user->exists ? $user->username : trans('cortex/fort::common.create_user') }}
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
                }).val('{{ $user->country_code }}').trigger('change');

            });
        })(jQuery);
    </script>
@endpush

{{-- Main Content --}}
@section('content')

    @if($user->exists)
        @include('cortex/foundation::backend.partials.confirm-deletion', ['type' => 'user'])
    @endif

    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <section class="content-header">
            <h1>{{ $user->exists ? $user->username : trans('cortex/fort::common.create_user') }}</h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('backend.home') }}"><i class="fa fa-dashboard"></i> {{ trans('cortex/foundation::common.backend') }}</a></li>
                <li><a href="{{ route('backend.users.index') }}">{{ trans('cortex/fort::common.users') }}</a></li>
                <li class="active">{{ $user->exists ? $user->username : trans('cortex/fort::common.create_user') }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            @if ($user->exists)
                {{ Form::model($user, ['url' => route('backend.users.update', ['user' => $user]), 'method' => 'put']) }}
            @else
                {{ Form::model($user, ['url' => route('backend.users.store')]) }}
            @endif

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#details" data-toggle="tab">{{ trans('cortex/fort::common.details') }}</a></li>
                        <li><a href="#social" data-toggle="tab">{{ trans('cortex/fort::common.social') }}</a></li>
                        <li><a href="#security" data-toggle="tab">{{ trans('cortex/fort::common.security') }}</a></li>
                        @if($user->exists) <li><a href="{{ route('backend.users.logs', ['user' => $user]) }}">{{ trans('cortex/fort::common.logs') }}</a></li> @endif
                        @if($user->exists && $currentUser->can('delete-users', $user)) <li class="pull-right"><a href="#" data-toggle="modal" data-target="#delete-confirmation" data-item-href="{{ route('backend.users.delete', ['user' => $user]) }}" data-item-name="{{ $user->slug }}"><i class="fa fa-trash text-danger"></i></a></li> @endif
                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane active" id="details">

                            <div class="row">
                                <div class="col-md-4">

                                    {{-- First Name --}}
                                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                        {{ Form::label('first_name', trans('cortex/fort::common.first_name'), ['class' => 'control-label']) }}
                                        {{ Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.first_name'), 'autofocus' => 'autofocus']) }}

                                        @if ($errors->has('first_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {{-- Middle Name --}}
                                    <div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
                                        {{ Form::label('middle_name', trans('cortex/fort::common.middle_name'), ['class' => 'control-label']) }}
                                        {{ Form::text('middle_name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.middle_name')]) }}

                                        @if ($errors->has('middle_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('middle_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {{-- Last Name --}}
                                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                        {{ Form::label('last_name', trans('cortex/fort::common.last_name'), ['class' => 'control-label']) }}
                                        {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.last_name')]) }}

                                        @if ($errors->has('last_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">

                                    {{-- Username --}}
                                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                        {{ Form::label('username', trans('cortex/fort::common.username'), ['class' => 'control-label']) }}
                                        {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.username'), 'required' => 'required']) }}

                                        @if ($errors->has('username'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {{-- Job Title --}}
                                    <div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
                                        {{ Form::label('job_title', trans('cortex/fort::common.job_title'), ['class' => 'control-label']) }}
                                        {{ Form::text('job_title', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.job_title')]) }}

                                        @if ($errors->has('job_title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('job_title') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-2">

                                    {{-- Name Prefix --}}
                                    <div class="form-group{{ $errors->has('name_prefix') ? ' has-error' : '' }}">
                                        {{ Form::label('name_prefix', trans('cortex/fort::common.name_prefix'), ['class' => 'control-label']) }}
                                        {{ Form::text('name_prefix', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.name_prefix')]) }}

                                        @if ($errors->has('name_prefix'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name_prefix') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-2">

                                    {{-- Name Suffix --}}
                                    <div class="form-group{{ $errors->has('name_suffix') ? ' has-error' : '' }}">
                                        {{ Form::label('name_suffix', trans('cortex/fort::common.name_suffix'), ['class' => 'control-label']) }}
                                        {{ Form::text('name_suffix', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.name_suffix')]) }}

                                        @if ($errors->has('name_suffix'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name_suffix') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Email --}}
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        {{ Form::label('email', trans('cortex/fort::common.email'), ['class' => 'control-label']) }}
                                        {{ Form::label('email_verified', trans('cortex/fort::common.verified'), ['class' => 'control-label pull-right']) }}

                                        <div class="input-group">
                                            {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.email'), 'required' => 'required']) }}
                                            <span class="input-group-addon">
                                                {{ Form::checkbox('email_verified') }}
                                            </span>
                                        </div>

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Country Code --}}
                                    <div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
                                        {{ Form::label('country_code', trans('cortex/fort::common.country'), ['class' => 'control-label']) }}
                                        {{ Form::select('country_code', [], null, ['class' => 'form-control select2', 'data-allow-clear' => true, 'placeholder' => trans('cortex/fort::common.select')]) }}

                                        @if ($errors->has('country_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('country_code') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Language Code --}}
                                    <div class="form-group{{ $errors->has('language_code') ? ' has-error' : '' }}">
                                        {{ Form::label('language_code', trans('cortex/fort::common.language'), ['class' => 'control-label']) }}
                                        {{ Form::select('language_code', $languages, null, ['class' => 'form-control select2', 'data-allow-clear' => true, 'placeholder' => trans('cortex/fort::common.select')]) }}

                                        @if ($errors->has('language_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('language_code') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Phone --}}
                                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        {{ Form::label('phone', trans('cortex/fort::common.phone'), ['class' => 'control-label']) }}
                                        {{ Form::label('phone_verified', trans('cortex/fort::common.verified'), ['class' => 'control-label pull-right']) }}

                                        <div class="input-group">
                                            {{ Form::number('phone', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.phone')]) }}
                                            <span class="input-group-addon">
                                        {{ Form::checkbox('phone_verified') }}
                                    </span>
                                        </div>

                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Gender --}}
                                    <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {{ Form::label('gender', trans('cortex/fort::common.gender'), ['class' => 'control-label']) }}
                                        {{ Form::select('gender', ['male' => trans('cortex/fort::common.male'), 'female' => trans('cortex/fort::common.female')], null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select'), 'data-allow-clear' => true, 'data-minimum-results-for-search' => 'Infinity']) }}

                                        @if ($errors->has('gender'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('gender') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Active --}}
                                    <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                                        {{ Form::label('active', trans('cortex/fort::common.active'), ['class' => 'control-label']) }}
                                        {{ Form::select('active', [1 => trans('cortex/fort::common.yes'), 0 => trans('cortex/fort::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity']) }}

                                        @if ($errors->has('active'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('active') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Birthday --}}
                                    <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                        {{ Form::label('birthday', trans('cortex/fort::common.birthday'), ['class' => 'control-label']) }}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>

                                            {{ Form::text('birthday', null, ['class' => 'form-control datepicker', 'data-auto-update-input' => 'false']) }}
                                        </div>

                                        @if ($errors->has('birthday'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('birthday') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="tab-pane" id="social">

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Twitter --}}
                                    <div class="form-group{{ $errors->has('twitter') ? ' has-error' : '' }}">
                                        {{ Form::label('twitter', trans('cortex/fort::common.twitter'), ['class' => 'control-label']) }}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-twitter"></i>
                                            </div>

                                            {{ Form::text('twitter', null, ['class' => 'form-control']) }}
                                        </div>

                                        @if ($errors->has('twitter'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('twitter') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Facebook --}}
                                    <div class="form-group{{ $errors->has('facebook') ? ' has-error' : '' }}">
                                        {{ Form::label('facebook', trans('cortex/fort::common.facebook'), ['class' => 'control-label']) }}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-facebook"></i>
                                            </div>

                                            {{ Form::text('facebook', null, ['class' => 'form-control']) }}
                                        </div>

                                        @if ($errors->has('facebook'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('facebook') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Linkedin --}}
                                    <div class="form-group{{ $errors->has('linkedin') ? ' has-error' : '' }}">
                                        {{ Form::label('linkedin', trans('cortex/fort::common.linkedin'), ['class' => 'control-label']) }}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-linkedin"></i>
                                            </div>

                                            {{ Form::text('linkedin', null, ['class' => 'form-control']) }}
                                        </div>

                                        @if ($errors->has('linkedin'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('linkedin') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Google Plus --}}
                                    <div class="form-group{{ $errors->has('google_plus') ? ' has-error' : '' }}">
                                        {{ Form::label('google_plus', trans('cortex/fort::common.google_plus'), ['class' => 'control-label']) }}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-google-plus"></i>
                                            </div>

                                            {{ Form::text('google_plus', null, ['class' => 'form-control']) }}
                                        </div>

                                        @if ($errors->has('google_plus'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('google_plus') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Skype --}}
                                    <div class="form-group{{ $errors->has('skype') ? ' has-error' : '' }}">
                                        {{ Form::label('skype', trans('cortex/fort::common.skype'), ['class' => 'control-label']) }}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-skype"></i>
                                            </div>

                                            {{ Form::text('skype', null, ['class' => 'form-control']) }}
                                        </div>

                                        @if ($errors->has('skype'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('skype') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    {{-- Wesbite --}}
                                    <div class="form-group{{ $errors->has('wesbite') ? ' has-error' : '' }}">
                                        {{ Form::label('wesbite', trans('cortex/fort::common.website'), ['class' => 'control-label']) }}
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-globe"></i>
                                            </div>

                                            {{ Form::text('wesbite', null, ['class' => 'form-control']) }}
                                        </div>

                                        @if ($errors->has('wesbite'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('wesbite') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="tab-pane" id="security">

                            <div class="row">

                                @can('assign-roles')

                                    <div class="col-md-4">

                                        {{-- Roles --}}
                                        <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                                            {{ Form::label('roleList[]', trans('cortex/fort::common.roles'), ['class' => 'control-label']) }}
                                            {{ Form::select('roleList[]', $roleList, null, ['class' => 'form-control', 'multiple' => 'multiple', 'size' => 4]) }}

                                            @if ($errors->has('roles'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('roles') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                    </div>

                                @endcan

                                @can('grant-abilities')

                                    <div class="col-md-4">

                                        {{-- Abilities --}}
                                        <div class="form-group{{ $errors->has('abilityList[]') ? ' has-error' : '' }}">
                                            {{ Form::label('abilityList[]', trans('cortex/fort::common.abilities'), ['class' => 'control-label']) }}
                                            {{ Form::select('abilityList[]', $abilityList, null, ['class' => 'form-control', 'multiple' => 'multiple', 'size' => 4]) }}

                                            @if ($errors->has('abilities'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('abilityList[]') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                    </div>

                                @endcan

                                <div class="col-md-4">

                                    {{-- Password --}}
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        {{ Form::label('password', trans('cortex/fort::common.password'), ['class' => 'control-label']) }}
                                        @if ($user->exists)
                                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password')]) }}
                                        @else
                                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password'), 'required' => 'required']) }}
                                        @endif

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <div class="pull-right">
                                    {{ Form::button(trans('cortex/fort::common.reset'), ['class' => 'btn btn-default btn-flat', 'type' => 'reset']) }}
                                    {{ Form::button(trans('cortex/fort::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                </div>

                                @include('cortex/foundation::backend.partials.timestamps', ['model' => $user])

                            </div>
                        </div>

                        {{--<div class="clearfix"></div>--}}

                    </div>

                </div>

            {{ Form::close() }}

        </section>

    </div>

@endsection
