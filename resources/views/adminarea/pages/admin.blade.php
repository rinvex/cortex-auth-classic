{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Adminarea\AdminFormRequest::class)->selector("#adminarea-cortex-auth-admins-create-form, #adminarea-cortex-auth-admins-{$admin->getRouteKey()}-update-form")->ignore('.skip-validation') !!}

    <script>
        window.countries = @json($countries);
        window.selectedCountry = '{{ old('country_code', $admin->country_code) }}';
    </script>
@endpush

{{-- Main Content --}}
@section('content')

    @includeWhen($admin->exists, 'cortex/foundation::adminarea.partials.modal', ['id' => 'delete-confirmation'])

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="nav-tabs-custom">
                @includeWhen($admin->exists, 'cortex/foundation::adminarea.partials.actions', ['name' => 'admin', 'model' => $admin, 'resource' => trans('cortex/auth::common.admin'), 'routePrefix' => 'adminarea.cortex.auth.admins'])
                {!! Menu::render('adminarea.cortex.auth.admins.tabs', 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="details-tab">

                        @if ($admin->exists)
                            {{ Form::model($admin, ['url' => route('adminarea.cortex.auth.admins.update', ['admin' => $admin]), 'id' => "adminarea-cortex-auth-admins-{$admin->getRouteKey()}-update-form", 'method' => 'put', 'files' => true]) }}
                        @else
                            {{ Form::model($admin, ['url' => route('adminarea.cortex.auth.admins.store'), 'id' => 'adminarea-cortex-auth-admins-create-form', 'files' => true]) }}
                        @endif

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Given Name --}}
                                    <div class="form-group{{ $errors->has('given_name') ? ' has-error' : '' }}">
                                        {{ Form::label('given_name', trans('cortex/auth::common.given_name'), ['class' => 'control-label']) }}
                                        {{ Form::text('given_name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.given_name'), 'data-slugify' => '[name="username"]', 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                        @if ($errors->has('given_name'))
                                            <span class="help-block">{{ $errors->first('given_name') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Family Name --}}
                                    <div class="form-group{{ $errors->has('family_name') ? ' has-error' : '' }}">
                                        {{ Form::label('family_name', trans('cortex/auth::common.family_name'), ['class' => 'control-label']) }}
                                        {{ Form::text('family_name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.family_name')]) }}

                                        @if ($errors->has('family_name'))
                                            <span class="help-block">{{ $errors->first('family_name') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Username --}}
                                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                        {{ Form::label('username', trans('cortex/auth::common.username'), ['class' => 'control-label']) }}
                                        {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.username'), 'required' => 'required']) }}

                                        @if ($errors->has('username'))
                                            <span class="help-block">{{ $errors->first('username') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Email --}}
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        {{ Form::label('email', trans('cortex/auth::common.email'), ['class' => 'control-label']) }}
                                        {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}

                                        @if ($errors->has('email'))
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Phone --}}
                                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        {{ Form::label('phone', trans('cortex/auth::common.phone'), ['class' => 'control-label']) }}
                                        {{ Form::tel('phone_input', $admin->phone, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.phone')]) }}

                                        <span class="help-block hide">{{ trans('cortex/foundation::messages.invalid_phone') }}</span>
                                        @if ($errors->has('phone'))
                                            <span class="help-block">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Is Active --}}
                                    <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
                                        {{ Form::label('is_active', trans('cortex/auth::common.is_active'), ['class' => 'control-label']) }}
                                        {{ Form::select('is_active', [1 => trans('cortex/auth::common.yes'), 0 => trans('cortex/auth::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                        @if ($errors->has('is_active'))
                                            <span class="help-block">{{ $errors->first('is_active') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Country Code --}}
                                    <div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
                                        {{ Form::label('country_code', trans('cortex/auth::common.country'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('country_code', '', ['class' => 'skip-validation', 'id' => 'country_code_hidden']) }}
                                        {{ Form::select('country_code', [], null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_country'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                        @if ($errors->has('country_code'))
                                            <span class="help-block">{{ $errors->first('country_code') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Language Code --}}
                                    <div class="form-group{{ $errors->has('language_code') ? ' has-error' : '' }}">
                                        {{ Form::label('language_code', trans('cortex/auth::common.language'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('language_code', '', ['class' => 'skip-validation', 'id' => 'language_code_hidden']) }}
                                        {{ Form::select('language_code', $languages, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_language'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                        @if ($errors->has('language_code'))
                                            <span class="help-block">{{ $errors->first('language_code') }}</span>
                                        @endif
                                    </div>

                                </div>


                                <div class="col-md-4">

                                    {{-- Title --}}
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        {{ Form::label('title', trans('cortex/auth::common.title'), ['class' => 'control-label']) }}
                                        {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.title')]) }}

                                        @if ($errors->has('title'))
                                            <span class="help-block">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Organization --}}
                                    <div class="form-group{{ $errors->has('organization') ? ' has-error' : '' }}">
                                        {{ Form::label('organization', trans('cortex/auth::common.organization'), ['class' => 'control-label']) }}
                                        {{ Form::text('organization', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.organization')]) }}

                                        @if ($errors->has('organization'))
                                            <span class="help-block">{{ $errors->first('organization') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Birthday --}}
                                    <div class="form-group has-feedback{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                        {{ Form::label('birthday', trans('cortex/auth::common.birthday'), ['class' => 'control-label']) }}
                                        {{ Form::text('birthday', null, ['class' => 'form-control daterangepicker', 'data-locale' => '{"format": "YYYY-MM-DD"}', 'data-single-date-picker' => 'true', 'data-show-dropdowns' => 'true', 'data-auto-apply' => 'true', 'data-min-date' => '1900-01-01']) }}
                                        <span class="fa fa-calendar form-control-feedback"></span>

                                        @if ($errors->has('birthday'))
                                            <span class="help-block">{{ $errors->first('birthday') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Gender --}}
                                    <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                        {{ Form::label('gender', trans('cortex/auth::common.gender'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('gender', '', ['class' => 'skip-validation', 'id' => 'gender_hidden']) }}
                                        {{ Form::select('gender', $genders, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_gender'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                                        @if ($errors->has('gender'))
                                            <span class="help-block">{{ $errors->first('gender') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Tags --}}
                                    <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                                        {{ Form::label('tags[]', trans('cortex/auth::common.tags'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('tags', '', ['class' => 'skip-validation']) }}
                                        {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'data-width' => '100%', 'data-tags' => 'true']) }}

                                        @if ($errors->has('tags'))
                                            <span class="help-block">{{ $errors->first('tags') }}</span>
                                        @endif
                                    </div>

                                </div>

                                @can('assign', app('cortex.auth.role'))

                                    <div class="col-md-4">

                                        {{-- Roles --}}
                                        <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                                            {{ Form::label('roles[]', trans('cortex/auth::common.roles'), ['class' => 'control-label']) }}
                                            {{ Form::hidden('roles', '', ['class' => 'skip-validation']) }}
                                            {{ Form::select('roles[]', $roles, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_roles'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('roles'))
                                                <span class="help-block">{{ $errors->first('roles') }}</span>
                                            @endif
                                        </div>

                                    </div>

                                @endcan

                                @can('grant', app('cortex.auth.ability'))

                                    <div class="col-md-4">

                                        {{-- Abilities --}}
                                        <div class="form-group{{ $errors->has('abilities') ? ' has-error' : '' }}">
                                            {{ Form::label('abilities[]', trans('cortex/auth::common.abilities'), ['class' => 'control-label']) }}
                                            {{ Form::hidden('abilities', '', ['class' => 'skip-validation']) }}
                                            {{ Form::select('abilities[]', $abilities, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_abilities'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('abilities'))
                                                <span class="help-block">{{ $errors->first('abilities') }}</span>
                                            @endif
                                        </div>

                                    </div>

                                @endcan

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Twitter --}}
                                    <div class="form-group{{ $errors->has('social.twitter') ? ' has-error' : '' }}">
                                        {{ Form::label('social[twitter]', trans('cortex/auth::common.twitter'), ['class' => 'control-label']) }}
                                        {{ Form::text('social[twitter]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.twitter')]) }}

                                        @if ($errors->has('social.twitter'))
                                            <span class="help-block">{{ $errors->first('social.twitter') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Facebook --}}
                                    <div class="form-group{{ $errors->has('social.facebook') ? ' has-error' : '' }}">
                                        {{ Form::label('social[facebook]', trans('cortex/auth::common.facebook'), ['class' => 'control-label']) }}
                                        {{ Form::text('social[facebook]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.facebook')]) }}

                                        @if ($errors->has('social.facebook'))
                                            <span class="help-block">{{ $errors->first('social.facebook') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Linkedin --}}
                                    <div class="form-group{{ $errors->has('social.linkedin') ? ' has-error' : '' }}">
                                        {{ Form::label('social[linkedin]', trans('cortex/auth::common.linkedin'), ['class' => 'control-label']) }}
                                        {{ Form::text('social[linkedin]', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.linkedin')]) }}

                                        @if ($errors->has('social.linkedin'))
                                            <span class="help-block">{{ $errors->first('social.linkedin') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Profile Picture --}}
                                    <div class="form-group has-feedback{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
                                        {{ Form::label('profile_picture', trans('cortex/auth::common.profile_picture'), ['class' => 'control-label']) }}

                                        <div class="input-group">
                                            {{ Form::text('profile_picture', null, ['class' => 'form-control file-name', 'placeholder' => trans('cortex/auth::common.profile_picture'), 'readonly' => 'readonly']) }}

                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                    {{ trans('cortex/auth::common.browse') }}
                                                    {{-- Skip Javascrip validation for file input fields to avoid size validation conflict with jquery.validator --}}
                                                    {{ Form::file('profile_picture', ['class' => 'form-control skip-validation', 'id' => 'profile_picture_browse']) }}
                                                </span>
                                            </span>
                                        </div>

                                        @if ($admin->exists && $admin->getMedia('profile_picture')->count())
                                            <i class="fa fa-paperclip"></i>
                                            <a href="{{ $admin->getFirstMediaUrl('profile_picture') }}" target="_blank">{{ $admin->getFirstMedia('profile_picture')->file_name }}</a> ({{ $admin->getFirstMedia('profile_picture')->human_readable_size }})
                                            <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                                               data-modal-action="{{ route('adminarea.cortex.auth.admins.media.destroy', ['admin' => $admin, 'media' => $admin->getFirstMedia('profile_picture')]) }}"
                                               data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                               data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                               data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => $admin->getFirstMedia('profile_picture')->getRouteKey()]) }}"
                                               title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-trash text-danger"></i></a>
                                        @endif

                                        @if ($errors->has('profile_picture'))
                                            <span class="help-block">{{ $errors->first('profile_picture') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Cover Photo --}}
                                    <div class="form-group has-feedback{{ $errors->has('cover_photo') ? ' has-error' : '' }}">
                                        {{ Form::label('cover_photo', trans('cortex/auth::common.cover_photo'), ['class' => 'control-label']) }}

                                        <div class="input-group">
                                            {{ Form::text('cover_photo', null, ['class' => 'form-control file-name', 'placeholder' => trans('cortex/auth::common.cover_photo'), 'readonly' => 'readonly']) }}

                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                    {{ trans('cortex/auth::common.browse') }}
                                                    {{-- Skip Javascrip validation for file input fields to avoid size validation conflict with jquery.validator --}}
                                                    {{ Form::file('cover_photo', ['class' => 'form-control skip-validation', 'id' => 'cover_photo_browse']) }}
                                                </span>
                                            </span>
                                        </div>

                                        @if ($admin->exists && $admin->getMedia('cover_photo')->count())
                                            <i class="fa fa-paperclip"></i>
                                            <a href="{{ $admin->getFirstMediaUrl('cover_photo') }}" target="_blank">{{ $admin->getFirstMedia('cover_photo')->file_name }}</a> ({{ $admin->getFirstMedia('cover_photo')->human_readable_size }})
                                            <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                                               data-modal-action="{{ route('adminarea.cortex.auth.admins.media.destroy', ['admin' => $admin, 'media' => $admin->getFirstMedia('cover_photo')]) }}"
                                               data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                               data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                               data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => $admin->getFirstMedia('cover_photo')->getRouteKey()]) }}"
                                               title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-trash text-danger"></i></a>
                                        @endif

                                        @if ($errors->has('cover_photo'))
                                            <span class="help-block">{{ $errors->first('cover_photo') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Timezone --}}
                                    <div class="form-group{{ $errors->has('timezone') ? ' has-error' : '' }}">
                                        {{ Form::label('timezone', trans('cortex/auth::common.timezone'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('timezone', '', ['class' => 'skip-validation', 'id' => 'timezone_hidden']) }}
                                        {{ Form::select('timezone', timezones(), null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_timezone'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                        @if ($errors->has('timezone'))
                                            <span class="help-block">{{ $errors->first('timezone') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Password --}}
                                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                                        {{ Form::label('password', trans('cortex/auth::common.password'), ['class' => 'control-label']) }}
                                        @if ($admin->exists)
                                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.password')]) }}
                                        @else
                                            {{ Form::password('password', ['class' => 'form-control autogenerate', 'placeholder' => trans('cortex/auth::common.password'), 'required' => 'required']) }}
                                        @endif
                                        <span class="fa fa-key form-control-feedback"></span>

                                        @if ($errors->has('password'))
                                            <span class="help-block">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Password Confirmation --}}
                                    <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        {{ Form::label('password_confirmation', trans('cortex/auth::common.password_confirmation'), ['class' => 'control-label']) }}
                                        @if ($admin->exists)
                                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.password_confirmation')]) }}
                                        @else
                                            {{ Form::password('password_confirmation', ['class' => 'form-control autogenerate', 'placeholder' => trans('cortex/auth::common.password_confirmation'), 'required' => 'required']) }}
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
                                        {{ Form::button(trans('cortex/auth::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>

                                    @include('cortex/foundation::adminarea.partials.timestamps', ['model' => $admin])

                                </div>

                            </div>

                        {{ Form::close() }}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
