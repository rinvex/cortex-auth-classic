{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Frontarea\AccountSettingsRequest::class)->selector('#frontarea-account-settings-form')->ignore('.skip-validation') !!}

    <script>
        window.countries = @json($countries);
        window.selectedCountry = '{{ old('country_code', request()->user()->country_code) }}';
    </script>
@endpush

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                @include('cortex/auth::frontarea.partials.sidebar')
            </div>
            <div class="col-md-9">
                <div class="profile-content">

                    {{ Form::model(request()->user(), ['url' => route('frontarea.cortex.auth.account.settings.update'), 'id' => 'frontarea-account-settings-form', 'files' => true]) }}

                        <div class="row">

                            <div class="col-md-4">

                                <div class="form-group{{ $errors->has('given_name') ? ' has-error' : '' }}">
                                    {{ Form::label('given_name', trans('cortex/auth::common.given_name'), ['class' => 'control-label']) }}
                                    {{ Form::text('given_name', null, ['class' => 'form-control', 'placeholder' => request()->user()->given_name ?: trans('cortex/auth::common.given_name'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                    @if ($errors->has('given_name'))
                                        <span class="help-block">{{ $errors->first('given_name') }}</span>
                                    @endif
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group{{ $errors->has('family_name') ? ' has-error' : '' }}">
                                    {{ Form::label('family_name', trans('cortex/auth::common.family_name'), ['class' => 'control-label']) }}
                                    {{ Form::text('family_name', null, ['class' => 'form-control', 'placeholder' => request()->user()->family_name ?: trans('cortex/auth::common.family_name')]) }}

                                    @if ($errors->has('family_name'))
                                        <span class="help-block">{{ $errors->first('family_name') }}</span>
                                    @endif
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                    {{ Form::label('username', trans('cortex/auth::common.username'), ['class' => 'control-label']) }}
                                    {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => request()->user()->username, 'required' => 'required']) }}

                                    @if ($errors->has('username'))
                                        <span class="help-block">{{ $errors->first('username') }}</span>
                                    @endif
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    {{ Form::label('email', trans('cortex/auth::common.email'), ['class' => 'control-label']) }}
                                    {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}

                                    @if (request()->user()->hasVerifiedEmail())
                                        <small class="text-success">{!! trans('cortex/auth::common.email_verified_at', ['date' => request()->user()->email_verified_at]) !!}</small>
                                    @elseif(request()->user()->email)
                                        <small class="text-danger">{!! trans('cortex/auth::common.email_unverified', ['href' => route('frontarea.cortex.auth.account.verification.email.request')]) !!}</small>
                                    @endif

                                    @if ($errors->has('email'))
                                        <span class="help-block">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4">

                                {{-- Title --}}
                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    {{ Form::label('title', trans('cortex/auth::common.title'), ['class' => 'control-label']) }}
                                    {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => request()->user()->title ?: trans('cortex/auth::common.title')]) }}

                                    @if ($errors->has('title'))
                                        <span class="help-block">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>

                            </div>

                            <div class="col-md-4">

                                {{-- Organization --}}
                                <div class="form-group{{ $errors->has('organization') ? ' has-error' : '' }}">
                                    {{ Form::label('organization', trans('cortex/auth::common.organization'), ['class' => 'control-label']) }}
                                    {{ Form::text('organization', null, ['class' => 'form-control', 'placeholder' => request()->user()->organization ?: trans('cortex/auth::common.organization')]) }}

                                    @if ($errors->has('organization'))
                                        <span class="help-block">{{ $errors->first('organization') }}</span>
                                    @endif
                                </div>

                            </div>

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

                                <div class="form-group has-feedback{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    {{ Form::label('phone', trans('cortex/auth::common.phone'), ['class' => 'control-label']) }}
                                    {{ Form::tel('phone_input', request()->user()->phone, ['class' => 'form-control', 'placeholder' => request()->user()->phone ?: trans('cortex/auth::common.phone')]) }}

                                    @if (request()->user()->hasVerifiedPhone())
                                        <small class="text-success">{!! trans('cortex/auth::common.phone_verified_at', ['date' => request()->user()->phone_verified_at]) !!}</small>
                                    @elseif(request()->user()->phone)
                                        <small class="text-danger">{!! trans('cortex/auth::common.phone_unverified', ['href' => route('frontarea.cortex.auth.account.verification.phone.request')]) !!}</small>
                                    @endif

                                    <span class="help-block hide">{{ trans('cortex/foundation::messages.invalid_phone') }}</span>
                                    @if ($errors->has('phone'))
                                        <span class="help-block">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                    {{ Form::label('gender', trans('cortex/auth::common.gender'), ['class' => 'control-label']) }}
                                    {{ Form::hidden('gender', '', ['class' => 'skip-validation', 'id' => 'gender_hidden']) }}
                                    {{ Form::select('gender', $genders, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_gender'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                                    @if ($errors->has('gender'))
                                        <span class="help-block">{{ $errors->first('gender') }}</span>
                                    @endif
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group has-feedback{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                    {{ Form::label('birthday', trans('cortex/auth::common.birthday'), ['class' => 'control-label']) }}
                                    {{ Form::date('birthday', null, ['class' => 'form-control daterangepicker', 'data-locale' => '{"format": "YYYY-MM-DD"}', 'data-single-date-picker' => 'true', 'data-show-dropdowns' => 'true', 'data-auto-apply' => 'true', 'data-min-date' => '1900-01-01']) }}
                                    <span class="fa fa-calendar form-control-feedback"></span>

                                    @if ($errors->has('birthday'))
                                        <span class="help-block">{{ $errors->first('birthday') }}</span>
                                    @endif
                                </div>

                            </div>

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

                                    @if (request()->user()->exists && request()->user()->getMedia('profile_picture')->count())
                                        <i class="fa fa-paperclip"></i>
                                        <a href="{{ request()->user()->getFirstMediaUrl('profile_picture') }}" target="_blank">{{ request()->user()->getFirstMedia('profile_picture')->file_name }}</a> ({{ request()->user()->getFirstMedia('profile_picture')->human_readable_size }})
                                        <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                                           data-modal-action="{{ route('frontarea.cortex.auth.account.media.destroy', ['member' => request()->user(), 'media' => request()->user()->getFirstMedia('profile_picture')]) }}"
                                           data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                           data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                           data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => request()->user()->getFirstMedia('profile_picture')->getRouteKey()]) }}"
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

                                    @if (request()->user()->exists && request()->user()->getMedia('cover_photo')->count())
                                        <i class="fa fa-paperclip"></i>
                                        <a href="{{ request()->user()->getFirstMediaUrl('cover_photo') }}" target="_blank">{{ request()->user()->getFirstMedia('cover_photo')->file_name }}</a> ({{ request()->user()->getFirstMedia('cover_photo')->human_readable_size }})
                                        <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                                           data-modal-action="{{ route('frontarea.cortex.auth.account.media.destroy', ['member' => request()->user(), 'media' => request()->user()->getFirstMedia('cover_photo')]) }}"
                                           data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                           data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                           data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => request()->user()->getFirstMedia('cover_photo')->getRouteKey()]) }}"
                                           title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-trash text-danger"></i></a>
                                    @endif

                                    @if ($errors->has('cover_photo'))
                                        <span class="help-block">{{ $errors->first('cover_photo') }}</span>
                                    @endif
                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center profile-buttons">
                                {{ Form::button('<i class="fa fa-save"></i> '.trans('cortex/auth::common.update_settings'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                            </div>
                        </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>

@endsection
