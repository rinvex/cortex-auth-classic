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
    <section class="container mx-auto my-6">
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
            @include('cortex/auth::frontarea.partials.sidebar')
            <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
                {{ Form::model(request()->user(), ['url' => route('frontarea.cortex.auth.account.settings.update'), 'id' => 'frontarea-account-settings-form', 'files' => true]) }}
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="{{ $errors->has('given_name') ? ' has-error' : '' }}">
                                {{ Form::label('given_name', trans('cortex/auth::common.given_name'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::text('given_name', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('given_name')?' border-red-500':' border-gray-300'), 'placeholder' => request()->user()->given_name ?: trans('cortex/auth::common.given_name'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                @if ($errors->has('given_name'))
                                    <span class="help-block">{{ $errors->first('given_name') }}</span>
                                @endif
                            </div>

                            <div class="{{ $errors->has('family_name') ? ' has-error' : '' }}">
                                {{ Form::label('family_name', trans('cortex/auth::common.family_name'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::text('family_name', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('family_name')?' border-red-500':' border-gray-300'), 'placeholder' => request()->user()->family_name ?: trans('cortex/auth::common.family_name')]) }}

                                @if ($errors->has('family_name'))
                                    <span class="help-block">{{ $errors->first('family_name') }}</span>
                                @endif
                            </div>

                            <div class="{{ $errors->has('username') ? ' has-error' : '' }}">
                                {{ Form::label('username', trans('cortex/auth::common.username'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::text('username', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('username')?' border-red-500':' border-gray-300'), 'placeholder' => request()->user()->username, 'required' => 'required']) }}

                                @if ($errors->has('username'))
                                    <span class="help-block">{{ $errors->first('username') }}</span>
                                @endif
                            </div>

                            <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                {{ Form::label('email', trans('cortex/auth::common.email'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::email('email', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('email')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}

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

                        <div class="grid grid-cols-3 gap-6">
                            {{-- Title --}}
                            <div class="{{ $errors->has('title') ? ' has-error' : '' }}">
                                {{ Form::label('title', trans('cortex/auth::common.title'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::text('title', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('title')?' border-red-500':' border-gray-300'), 'placeholder' => request()->user()->title ?: trans('cortex/auth::common.title')]) }}

                                @if ($errors->has('title'))
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                @endif
                            </div>

                            {{-- Organization --}}
                            <div class="{{ $errors->has('organization') ? ' has-error' : '' }}">
                                {{ Form::label('organization', trans('cortex/auth::common.organization'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::text('organization', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('organization')?' border-red-500':' border-gray-300'), 'placeholder' => request()->user()->organization ?: trans('cortex/auth::common.organization')]) }}

                                @if ($errors->has('organization'))
                                    <span class="help-block">{{ $errors->first('organization') }}</span>
                                @endif
                            </div>

                            {{-- Country Code --}}
                            <div class="{{ $errors->has('country_code') ? ' has-error' : '' }}">
                                {{ Form::label('country_code', trans('cortex/auth::common.country'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::hidden('country_code', '', ['class' => 'skip-validation', 'id' => 'country_code_hidden']) }}
                                {{ Form::select('country_code', [], null, ['class' => 'mt-1 block w-full bg-white border border-gray-300  shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm select2', 'placeholder' => trans('cortex/auth::common.select_country'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                @if ($errors->has('country_code'))
                                    <span class="help-block">{{ $errors->first('country_code') }}</span>
                                @endif
                            </div>

                            <div class="{{ $errors->has('language_code') ? ' has-error' : '' }}">
                                {{ Form::label('language_code', trans('cortex/auth::common.language'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::hidden('language_code', '', ['class' => 'skip-validation', 'id' => 'language_code_hidden']) }}
                                {{ Form::select('language_code', $languages, null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm select2', 'placeholder' => trans('cortex/auth::common.select_language'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                @if ($errors->has('language_code'))
                                    <span class="help-block">{{ $errors->first('language_code') }}</span>
                                @endif
                            </div>

                            {{-- Timezone --}}
                            <div class="{{ $errors->has('timezone') ? ' has-error' : '' }}">
                                {{ Form::label('timezone', trans('cortex/auth::common.timezone'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::hidden('timezone', '', ['class' => 'skip-validation', 'id' => 'timezone_hidden']) }}
                                {{ Form::select('timezone', timezones(), null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm select2', 'placeholder' => trans('cortex/auth::common.select_timezone'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                                @if ($errors->has('timezone'))
                                    <span class="help-block">{{ $errors->first('timezone') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">

                            <div class=" has-feedback{{ $errors->has('phone') ? ' has-error' : '' }}">
                                {{ Form::label('phone', trans('cortex/auth::common.phone'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::tel('phone_input', request()->user()->phone, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('phone')?' border-red-500':' border-gray-300'), 'placeholder' => request()->user()->phone ?: trans('cortex/auth::common.phone')]) }}

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

                            <div class="{{ $errors->has('gender') ? ' has-error' : '' }}">
                                {{ Form::label('gender', trans('cortex/auth::common.gender'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::hidden('gender', '', ['class' => 'skip-validation', 'id' => 'gender_hidden']) }}
                                {{ Form::select('gender', $genders, null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm select2'.($errors->has('gender')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.select_gender'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                                @if ($errors->has('gender'))
                                    <span class="help-block">{{ $errors->first('gender') }}</span>
                                @endif
                            </div>

                            <div class=" has-feedback{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                {{ Form::label('birthday', trans('cortex/auth::common.birthday'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::date('birthday', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm datepicker'.($errors->has('birthday')?' border-red-500':' border-gray-300'), 'data-locale' => '{"format": "YYYY-MM-DD"}', 'data-single-date-picker' => 'true', 'data-show-dropdowns' => 'true', 'data-auto-apply' => 'true', 'data-min-date' => '1900-01-01']) }}
                                <span class="fa fa-calendar form-control-feedback"></span>

                                @if ($errors->has('birthday'))
                                    <span class="help-block">{{ $errors->first('birthday') }}</span>
                                @endif
                            </div>

                            {{-- Twitter --}}
                            <div class="{{ $errors->has('social.twitter') ? ' has-error' : '' }}">
                                {{ Form::label('social[twitter]', trans('cortex/auth::common.twitter'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::text('social[twitter]', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('social.twitter')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.twitter')]) }}

                                @if ($errors->has('social.twitter'))
                                    <span class="help-block">{{ $errors->first('social.twitter') }}</span>
                                @endif
                            </div>

                            {{-- Facebook --}}
                            <div class="{{ $errors->has('social.facebook') ? ' has-error' : '' }}">
                                {{ Form::label('social[facebook]', trans('cortex/auth::common.facebook'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::text('social[facebook]', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('social.facebook')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.facebook')]) }}

                                @if ($errors->has('social.facebook'))
                                    <span class="help-block">{{ $errors->first('social.facebook') }}</span>
                                @endif
                            </div>

                            {{-- Linkedin --}}
                            <div class="{{ $errors->has('social.linkedin') ? ' has-error' : '' }}">
                                {{ Form::label('social[linkedin]', trans('cortex/auth::common.linkedin'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::text('social[linkedin]', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('social.linkedin')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.linkedin')]) }}
                                @if ($errors->has('social.linkedin'))
                                    <span class="help-block">{{ $errors->first('social.linkedin') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            {{-- Profile Picture --}}
                            <div class=" has-feedback{{ $errors->has('profile_picture') ? ' has-error' : '' }}">
                                {{ Form::label('profile_picture', trans('cortex/auth::common.profile_picture'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                <div class="mt-1 flex items-center">
                                    @if (request()->user()->exists && request()->user()->getMedia('profile_picture')->count())
                                        <span class="inline-block bg-gray-100 rounded-full overflow-hidden h-12 w-12">
                                            <img src="{{ request()->user()->getFirstMediaUrl('profile_picture') }}" class="h-full w-full text-gray-300">
                                        </span>
                                    @else
                                        <span class="inline-block bg-gray-100 rounded-full overflow-hidden h-12 w-12">
                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                              <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </span>
                                    @endif
                                    <span class="input-group-btn">
                                        <label for="profile_picture_browse" class="ml-5 bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 btn-file">
                                            {{ trans('cortex/auth::common.browse') }}
                                            {{-- Skip Javascrip validation for file input fields to avoid size validation conflict with jquery.validator --}}
                                            {{ Form::file('profile_picture', ['class' => ' sr-only skip-validation '.($errors->has('profile_picture')?' border-red-500':' border-gray-300'), 'id' => 'profile_picture_browse']) }}
                                        </label>
                                    </span>
                                </div>
                                @if (request()->user()->exists && request()->user()->getMedia('profile_picture')->count())
                                    <i class="fa fa-paperclip"></i>
                                    <a href="{{ request()->user()->getFirstMediaUrl('profile_picture') }}"
                                       target="_blank">{{ request()->user()->getFirstMedia('profile_picture')->file_name }}</a>
                                    ({{ request()->user()->getFirstMedia('profile_picture')->human_readable_size }})
                                    <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                                       data-modal-action="{{ route('frontarea.cortex.auth.account.media.destroy', ['member' => request()->user(), 'media' => request()->user()->getFirstMedia('profile_picture')]) }}"
                                       data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                       data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                       data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => request()->user()->getFirstMedia('profile_picture')->getRouteKey()]) }}"
                                       title="{{ trans('cortex/foundation::common.delete') }}"><i
                                                class="fa fa-trash text-danger"></i></a>
                                @endif
                                @if ($errors->has('profile_picture'))
                                    <span class="help-block">{{ $errors->first('profile_picture') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            {{-- Cover Photo --}}
                            <div class="has-feedback{{ $errors->has('cover_photo') ? ' has-error' : '' }}">
                                {{ Form::label('cover_photo', trans('cortex/auth::common.cover_photo'), ['class' => 'block text-sm font-medium text-gray-700']) }}

                                <div class="mt-1 border-2 border-gray-300 border-dashed rounded-md px-6 pt-5 pb-6 flex justify-center">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="cover_photo_browse" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>{{ trans('cortex/auth::common.browse') }}</span>
                                                {{-- Skip Javascrip validation for file input fields to avoid size validation conflict with jquery.validator --}}
                                                {{ Form::file('cover_photo', ['class' => 'sr-only skip-validation'.($errors->has('cover_photo')?' border-red-500':' border-gray-300'), 'id' => 'cover_photo_browse']) }}
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                    </div>
                                </div>

                                @if (request()->user()->exists && request()->user()->getMedia('cover_photo')->count())
                                    <i class="fa fa-paperclip"></i>
                                    <a href="{{ request()->user()->getFirstMediaUrl('cover_photo') }}"
                                       target="_blank">{{ request()->user()->getFirstMedia('cover_photo')->file_name }}</a>
                                    ({{ request()->user()->getFirstMedia('cover_photo')->human_readable_size }})
                                    <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                                       data-modal-action="{{ route('frontarea.cortex.auth.account.media.destroy', ['member' => request()->user(), 'media' => request()->user()->getFirstMedia('cover_photo')]) }}"
                                       data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                       data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                       data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/foundation::common.media'), 'identifier' => request()->user()->getFirstMedia('cover_photo')->getRouteKey()]) }}"
                                       title="{{ trans('cortex/foundation::common.delete') }}"><i
                                                class="fa fa-trash text-danger"></i></a>
                                @endif

                                @if ($errors->has('cover_photo'))
                                    <span class="help-block">{{ $errors->first('cover_photo') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit"
                                class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ trans('cortex/auth::common.update_settings') }}
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection
