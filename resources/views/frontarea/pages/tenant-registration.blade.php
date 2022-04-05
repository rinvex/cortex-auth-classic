{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Frontarea\TenantRegistrationProcessRequest::class)->selector("#frontarea-tenant-registration-form")->ignore('.skip-validation') !!}

    <script>
        window.countries = @json($countries);
        window.selectedCountry = '{{ old('tenant.country_code') }}';
    </script>
@endpush

{{-- Main Content --}}
@section('content')

    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">{{ trans('cortex/auth::common.account_register') }}</h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white pt-3 pb-8 px-4 shadow sm:rounded-lg sm:px-10">
                {{ Form::open(['url' => route('frontarea.cortex.auth.account.register.tenant.process'), 'id' => 'frontarea-tenant-registration-form', 'class' => 'space-y-6']) }}

                <h3 class="text-xl pb-6 text-center">{{ trans('cortex/auth::common.manager_account') }}</h3>

                <div class="has-feedback{{ $errors->has('manager.given_name') ? ' has-error' : '' }}">
                    {{ Form::text('manager[given_name]', old('manager.given_name'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('manager.given_name')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.given_name'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                    @if ($errors->has('manager.given_name'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('manager.given_name') }}</p>
                    @endif
                </div>

                <div class="has-feedback{{ $errors->has('manager.family_name') ? ' has-error' : '' }}">
                    {{ Form::text('manager[family_name]', old('manager.family_name'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('manager.family_name')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.family_name')]) }}

                    @if ($errors->has('manager.family_name'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('manager.family_name') }}</p>
                    @endif
                </div>

                <div class="has-feedback{{ $errors->has('manager.username') ? ' has-error' : '' }}">
                    {{ Form::text('manager[username]', old('manager.username'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('manager.username')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.username'), 'required' => 'required']) }}

                    @if ($errors->has('manager.username'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('manager.username') }}</p>
                    @endif
                </div>

                <div class=" has-feedback{{ $errors->has('manager.email') ? ' has-error' : '' }}">
                    {{ Form::email('manager[email]', old('manager.email'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('manager.email')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}

                    @if ($errors->has('manager.email'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('manager.email') }}</p>
                    @endif
                </div>

                <div class=" has-feedback{{ $errors->has('manager.password') ? ' has-error' : '' }}">
                    {{ Form::password('manager[password]', ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('manager.password')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.password'), 'required' => 'required']) }}

                    @if ($errors->has('manager.password'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('manager.password') }}</p>
                    @endif
                </div>

                <div class=" has-feedback{{ $errors->has('manager.password_confirmation') ? ' has-error' : '' }}">
                    {{ Form::password('manager[password_confirmation]', ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('manager.password_confirmation')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.password_confirmation'), 'required' => 'required']) }}

                    @if ($errors->has('manager.password_confirmation'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('manager.password_confirmation') }}</p>
                    @endif
                </div>

                <h3 class="text-xl pb-6 text-center">{{ trans('cortex/auth::common.tenant_details') }}</h3>

                <div class=" has-feedback{{ $errors->has('tenant.title') ? ' has-error' : '' }}">
                    {{ Form::text('tenant[title]', old('tenant.title'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('tenant.title')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.title'), 'data-slugify' => '[name="tenant\[name\]"]', 'required' => 'required']) }}

                    @if ($errors->has('tenant.title'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('tenant.title') }}</p>
                    @endif
                </div>

                <div class=" has-feedback{{ $errors->has('tenant.slug') ? ' has-error' : '' }}">
                    {{ Form::text('tenant[slug]', old('tenant.slug'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('tenant.slug')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.slug'), 'required' => 'required']) }}

                    @if ($errors->has('tenant.slug'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('tenant.slug') }}</p>
                    @endif
                </div>

                <div class=" has-feedback{{ $errors->has('tenant.email') ? ' has-error' : '' }}">
                    {{ Form::text('tenant[email]', old('tenant.email'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('tenant.email')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}

                    @if ($errors->has('tenant.email'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('tenant.email') }}</p>
                    @endif
                </div>

                <div class=" has-feedback{{ $errors->has('tenant.country_code') ? ' has-error' : '' }}">
                    {{ Form::hidden('tenant[country_code]', '', ['class' => 'skip-validation']) }}
                    {{ Form::select('tenant[country_code]', [], null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm select2 input-lg', 'placeholder' => trans('cortex/auth::common.select_country'), 'required' => 'required', 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                    @if ($errors->has('tenant.country_code'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('tenant.country_code') }}</p>
                    @endif
                </div>

                <div class="{{ $errors->has('tenant.language_code') ? ' has-error' : '' }}">
                    {{ Form::hidden('tenant[language_code]', '') }}
                    {{ Form::select('tenant[language_code]', $languages, null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm select2', 'placeholder' => trans('cortex/auth::common.select_language'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}

                    @if ($errors->has('tenant.language_code'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('tenant.language_code') }}</p>
                    @endif
                </div>

                <div>
                    {{ Form::button('<i class="m-1 fa fa-user-plus"></i> '.trans('cortex/auth::common.register'), ['class' => 'w-full flex justify-center py-2 px-4 border border-transparent  shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500', 'type' => 'submit']) }}
                </div>

                {{ Form::close() }}
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500"> Or </span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <div>
                            {{ Html::link(route('frontarea.cortex.auth.account.login'), trans('cortex/auth::common.account_login')) }}
                        </div>
                        <div>
                            {{ Html::link(route('frontarea.cortex.auth.account.passwordreset.request'), trans('cortex/auth::common.passwordreset')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
