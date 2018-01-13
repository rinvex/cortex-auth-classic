{{-- Master Layout --}}
@extends('cortex/tenants::managerarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/tenants::common.managerarea') }} » {{ trans('cortex/fort::common.users') }} » {{ $user->exists ? $user->username : trans('cortex/fort::common.create_user') }}
@stop

@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Managerarea\UserFormRequest::class)->selector("#managerarea-users-create-form, #managerarea-users-{$user->getKey()}-update-form") !!}

    <script>
        (function($) {
            $(function() {
                var countries = [
                    @foreach($countries as $code => $country)
                        { id: '{{ $code }}', text: '{{ $country['name'] }}', emoji: '{{ $country['emoji'] }}' },
                    @endforeach
                ];

                function formatCountry (country_code) {
                    if (! country_code.id) {
                        return country_code.text;
                    }

                    var $country = $(
                        '<span style="padding-right: 10px">' + country_code.emoji + '</span>' +
                        '<span>' + country_code.text + '</span>'
                    );

                    return $country;
                };

                $("select[name='country_code']").select2({
                    placeholder: "Select a country",
                    templateSelection: formatCountry,
                    templateResult: formatCountry,
                    data: countries
                }).val('{{ old('country_code', $user->country_code) }}').trigger('change');

            });
        })(jQuery);
    </script>
@endpush

{{-- Main Content --}}
@section('content')

    @if($user->exists)
        @include('cortex/foundation::common.partials.confirm-deletion', ['type' => 'user'])
    @endif

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#details-tab" data-toggle="tab">{{ trans('cortex/fort::common.details') }}</a></li>
                    {!! Tab::headers('cortex.fort.user.tabs', $user) !!}
                    @if($user->exists) <li><a href="#logs-tab" data-toggle="tab">{{ trans('cortex/fort::common.logs') }}</a></li> @endif
                    @if($user->exists) <li><a href="#activities-tab" data-toggle="tab">{{ trans('cortex/fort::common.activities') }}</a></li> @endif
                    @if($user->exists && $currentUser->can('delete-users', $user)) <li class="pull-right"><a href="#" data-toggle="modal" data-target="#delete-confirmation" data-item-href="{{ route('managerarea.users.delete', ['user' => $user]) }}" data-item-name="{{ $user->slug }}"><i class="fa fa-trash text-danger"></i></a></li> @endif
                </ul>

                <div class="tab-content">

                    {!! Tab::panels('cortex.fort.user.tabs', $user) !!}

                    <div class="tab-pane active" id="details-tab">

                        @if ($user->exists)
                            {{ Form::model($user, ['url' => route('managerarea.users.update', ['user' => $user]), 'id' => "managerarea-users-{$user->getKey()}-update-form", 'method' => 'put', 'files' => true]) }}
                        @else
                            {{ Form::model($user, ['url' => route('managerarea.users.store'), 'id' => 'managerarea-users-create-form', 'files' => true]) }}
                        @endif

                            <div class="row">
                                <div class="col-md-4">

                                    {{-- First Name --}}
                                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                        {{ Form::label('first_name', trans('cortex/fort::common.first_name'), ['class' => 'control-label']) }}
                                        {{ Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.first_name'), 'autofocus' => 'autofocus']) }}

                                        @if ($errors->has('first_name'))
                                            <span class="help-block">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {{-- Middle Name --}}
                                    <div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
                                        {{ Form::label('middle_name', trans('cortex/fort::common.middle_name'), ['class' => 'control-label']) }}
                                        {{ Form::text('middle_name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.middle_name')]) }}

                                        @if ($errors->has('middle_name'))
                                            <span class="help-block">{{ $errors->first('middle_name') }}</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {{-- Last Name --}}
                                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                        {{ Form::label('last_name', trans('cortex/fort::common.last_name'), ['class' => 'control-label']) }}
                                        {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.last_name')]) }}

                                        @if ($errors->has('last_name'))
                                            <span class="help-block">{{ $errors->first('last_name') }}</span>
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
                                            <span class="help-block">{{ $errors->first('username') }}</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {{-- Job Title --}}
                                    <div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
                                        {{ Form::label('job_title', trans('cortex/fort::common.job_title'), ['class' => 'control-label']) }}
                                        {{ Form::text('job_title', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.job_title')]) }}

                                        @if ($errors->has('job_title'))
                                            <span class="help-block">{{ $errors->first('job_title') }}</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-2">

                                    {{-- Name Prefix --}}
                                    <div class="form-group{{ $errors->has('name_prefix') ? ' has-error' : '' }}">
                                        {{ Form::label('name_prefix', trans('cortex/fort::common.name_prefix'), ['class' => 'control-label']) }}
                                        {{ Form::text('name_prefix', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.name_prefix')]) }}

                                        @if ($errors->has('name_prefix'))
                                            <span class="help-block">{{ $errors->first('name_prefix') }}</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-2">

                                    {{-- Name Suffix --}}
                                    <div class="form-group{{ $errors->has('name_suffix') ? ' has-error' : '' }}">
                                        {{ Form::label('name_suffix', trans('cortex/fort::common.name_suffix'), ['class' => 'control-label']) }}
                                        {{ Form::text('name_suffix', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.name_suffix')]) }}

                                        @if ($errors->has('name_suffix'))
                                            <span class="help-block">{{ $errors->first('name_suffix') }}</span>
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
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Country Code --}}
                                    <div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
                                        {{ Form::label('country_code', trans('cortex/fort::common.country'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('country_code', '') }}
                                        {{ Form::select('country_code', [], null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_country'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                        @if ($errors->has('country_code'))
                                            <span class="help-block">{{ $errors->first('country_code') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Language Code --}}
                                    <div class="form-group{{ $errors->has('language_code') ? ' has-error' : '' }}">
                                        {{ Form::label('language_code', trans('cortex/fort::common.language'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('language_code', '') }}
                                        {{ Form::select('language_code', $languages, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_language'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                        @if ($errors->has('language_code'))
                                            <span class="help-block">{{ $errors->first('language_code') }}</span>
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
                                            <span class="help-block">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Gender --}}
                                    <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {{ Form::label('gender', trans('cortex/fort::common.gender'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('gender', '') }}
                                        {{ Form::select('gender', $genders, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_gender'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                                        @if ($errors->has('gender'))
                                            <span class="help-block">{{ $errors->first('gender') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Active --}}
                                    <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
                                        {{ Form::label('is_active', trans('cortex/fort::common.active'), ['class' => 'control-label']) }}
                                        {{ Form::select('is_active', [1 => trans('cortex/fort::common.yes'), 0 => trans('cortex/fort::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                                        @if ($errors->has('is_active'))
                                            <span class="help-block">{{ $errors->first('is_active') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Birthday --}}
                                    <div class="form-group has-feedback{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                        {{ Form::label('birthday', trans('cortex/fort::common.birthday'), ['class' => 'control-label']) }}
                                        {{ Form::text('birthday', null, ['class' => 'form-control datepicker', 'data-auto-update-input' => 'false']) }}
                                        <span class="fa fa-calendar form-control-feedback"></span>

                                        @if ($errors->has('birthday'))
                                            <span class="help-block">{{ $errors->first('birthday') }}</span>
                                        @endif
                                    </div>

                                </div>

                                @can('assign-roles')

                                    <div class="col-md-4">

                                        {{-- Roles --}}
                                        <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                                            {{ Form::label('roles[]', trans('cortex/fort::common.roles'), ['class' => 'control-label']) }}
                                            {{ Form::hidden('roles', '') }}
                                            {{ Form::select('roles[]', $roles, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_roles'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('roles'))
                                                <span class="help-block">{{ $errors->first('roles') }}</span>
                                            @endif
                                        </div>

                                    </div>

                                @endcan

                                @can('grant-abilities')

                                    <div class="col-md-4">

                                        {{-- Abilities --}}
                                        <div class="form-group{{ $errors->has('abilities') ? ' has-error' : '' }}">
                                            {{ Form::label('abilities[]', trans('cortex/fort::common.abilities'), ['class' => 'control-label']) }}
                                            {{ Form::hidden('abilities', '') }}
                                            {{ Form::select('abilities[]', $abilities, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_abilities'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('abilities'))
                                                <span class="help-block">{{ $errors->first('abilities') }}</span>
                                            @endif
                                        </div>

                                    </div>

                                @endcan

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Profile Picture --}}
                                    <div class="form-group has-feedback{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
                                        {{ Form::label('profile_picture', trans('cortex/fort::common.profile_picture'), ['class' => 'control-label']) }}

                                        <div class="input-group">
                                            {{ Form::text('profile_picture', null, ['class' => 'form-control file-name', 'placeholder' => trans('cortex/fort::common.profile_picture'), 'readonly' => 'readonly']) }}

                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                    {{ trans('cortex/fort::common.browse') }}
                                                    {{ Form::file('profile_picture', ['class' => 'form-control']) }}
                                                </span>
                                            </span>
                                        </div>

                                        @if ($user->exists && $user->getMedia('profile_picture')->count())
                                            <i class="fa fa-paperclip"></i>
                                            <a href="{{ $user->getFirstMediaUrl('profile_picture') }}" target="_blank">{{ $user->getFirstMedia('profile_picture')->file_name }}</a> ({{ $user->getFirstMedia('profile_picture')->human_readable_size }})
                                            <a href="{{ route('managerarea.users.media.delete', ['user' => $user, 'media' => $user->getFirstMedia('profile_picture')]) }}" data-method="delete" data-token="{{ csrf_token() }}" class="close" style="float: none; font-size: inherit;" title="{{ trans('cortex/foundation::common.delete') }}"><i class="text-danger fa fa-close"></i></a>
                                        @endif


                                        @if ($errors->has('profile_picture'))
                                            <span class="help-block">{{ $errors->first('profile_picture') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Cover Photo --}}
                                    <div class="form-group has-feedback{{ $errors->has('cover_photo') ? ' has-error' : '' }}">
                                        {{ Form::label('cover_photo', trans('cortex/fort::common.cover_photo'), ['class' => 'control-label']) }}

                                        <div class="input-group">
                                            {{ Form::text('cover_photo', null, ['class' => 'form-control file-name', 'placeholder' => trans('cortex/fort::common.cover_photo'), 'readonly' => 'readonly']) }}

                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                    {{ trans('cortex/fort::common.browse') }}
                                                    {{ Form::file('cover_photo', ['class' => 'form-control']) }}
                                                </span>
                                            </span>
                                        </div>

                                        @if ($user->exists && $user->getMedia('cover_photo')->count())
                                            <i class="fa fa-paperclip"></i>
                                            <a href="{{ $user->getFirstMediaUrl('cover_photo') }}" target="_blank">{{ $user->getFirstMedia('cover_photo')->file_name }}</a> ({{ $user->getFirstMedia('cover_photo')->human_readable_size }})
                                            <a href="{{ route('managerarea.users.media.delete', ['user' => $user, 'media' => $user->getFirstMedia('cover_photo')]) }}" data-method="delete" data-token="{{ csrf_token() }}" class="close" style="float: none; font-size: inherit;" title="{{ trans('cortex/foundation::common.delete') }}"><i class="text-danger fa fa-close"></i></a>
                                        @endif


                                        @if ($errors->has('cover_photo'))
                                            <span class="help-block">{{ $errors->first('cover_photo') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Password --}}
                                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                                        {{ Form::label('password', trans('cortex/fort::common.password'), ['class' => 'control-label']) }}
                                        @if ($user->exists)
                                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password')]) }}
                                        @else
                                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password'), 'required' => 'required']) }}
                                        @endif
                                        <span class="fa fa-key form-control-feedback"></span>

                                        @if ($errors->has('password'))
                                            <span class="help-block">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Password Confirmation --}}
                                    <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        {{ Form::label('password_confirmation', trans('cortex/fort::common.password_confirmation'), ['class' => 'control-label']) }}
                                        @if ($user->exists)
                                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password_confirmation')]) }}
                                        @else
                                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.password_confirmation'), 'required' => 'required']) }}
                                        @endif
                                        <span class="fa fa-key form-control-feedback"></span>

                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="pull-right">
                                        {{ Form::button(trans('cortex/fort::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>

                                    @include('cortex/tenants::managerarea.partials.timestamps', ['model' => $user])

                                </div>

                            </div>

                        {{ Form::close() }}

                    </div>

                    @if($user->exists)

                        <div class="tab-pane" id="logs-tab">
                            {!! $logs->table(['class' => 'table table-striped table-hover responsive dataTableBuilder', 'id' => "managerarea-users-{$user->getKey()}-logs-table"]) !!}
                        </div>

                        <div class="tab-pane" id="activities-tab">
                            {!! $activities->table(['class' => 'table table-striped table-hover responsive dataTableBuilder', 'id' => "managerarea-users-{$user->getKey()}-activities-table"]) !!}
                        </div>

                    @endif

                </div>

            </div>

        </section>

    </div>

@endsection

@if($user->exists)

    @push('styles')
        <link href="{{ mix('css/datatables.css', 'assets') }}" rel="stylesheet">
    @endpush

    @push('scripts-vendor')
        <script src="{{ mix('js/datatables.js', 'assets') }}" type="text/javascript"></script>
    @endpush

    @push('scripts')
        {!! $logs->scripts() !!}
        {!! $activities->scripts() !!}
    @endpush

@endif
