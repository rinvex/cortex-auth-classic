{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Frontarea\MemberRegistrationProcessRequest::class)->selector('#frontarea-member-registration-form')->ignore('.skip-validation') !!}
@endpush
{{-- Main Content --}}
@section('content')

    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">{{ trans('cortex/auth::common.account_register') }}</h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                {{ Form::open(['url' => route('frontarea.cortex.auth.account.register.member.process'), 'id' => 'frontarea-member-registration-form', 'class' => 'space-y-6']) }}

                <div>
                    <div class="mt-1">
                        {{ Form::text('given_name', old('given_name'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('given_name')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.given_name'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                        @if ($errors->has('given_name'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('given_name') }}</p>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="mt-1">
                        {{ Form::text('family_name', old('family_name'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('family_name')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.family_name'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                        @if ($errors->has('family_name'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('family_name') }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="mt-1">
                        {{ Form::text('username', old('username'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('username')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.username'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                        @if ($errors->has('username'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('username') }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="mt-1">
                        {{ Form::text('email', old('email'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('email')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                        @if ($errors->has('email'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="mt-1">
                        {{ Form::password('password', ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('password')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.password'), 'required' => 'required']) }}
                        @if ($errors->has('password'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="mt-1">
                        {{ Form::password('password_confirmation', ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('password_confirmation')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.password_confirmation'), 'required' => 'required']) }}
                        @if ($errors->has('password_confirmation'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ trans('cortex/auth::common.register') }}</button>
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


{{--    {{ Form::open(['url' => route('frontarea.cortex.auth.account.register.member.process'), 'id' => 'frontarea-member-registration-form', 'role' => 'auth']) }}--}}

{{--    <div class="centered"><strong>{{ trans('cortex/auth::common.account_register') }}</strong></div>--}}

{{--    <div class="form-group has-feedback{{ $errors->has('given_name') ? ' has-error' : '' }}">--}}
{{--        {{ Form::text('given_name', old('given_name'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.given_name'), 'required' => 'required', 'autofocus' => 'autofocus']) }}--}}

{{--        @if ($errors->has('given_name'))--}}
{{--            <span class="help-block">{{ $errors->first('given_name') }}</span>--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    <div class="form-group has-feedback{{ $errors->has('family_name') ? ' has-error' : '' }}">--}}
{{--        {{ Form::text('family_name', old('family_name'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.family_name')]) }}--}}

{{--        @if ($errors->has('family_name'))--}}
{{--            <span class="help-block">{{ $errors->first('family_name') }}</span>--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    <div class="form-group has-feedback{{ $errors->has('username') ? ' has-error' : '' }}">--}}
{{--        {{ Form::text('username', old('username'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.username'), 'required' => 'required']) }}--}}

{{--        @if ($errors->has('username'))--}}
{{--            <span class="help-block">{{ $errors->first('username') }}</span>--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">--}}
{{--        {{ Form::email('email', old('email'), ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}--}}

{{--        @if ($errors->has('email'))--}}
{{--            <span class="help-block">{{ $errors->first('email') }}</span>--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">--}}
{{--        {{ Form::password('password', ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.password'), 'required' => 'required']) }}--}}

{{--        @if ($errors->has('password'))--}}
{{--            <span class="help-block">{{ $errors->first('password') }}</span>--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">--}}
{{--        {{ Form::password('password_confirmation', ['class' => 'form-control input-lg', 'placeholder' => trans('cortex/auth::common.password_confirmation'), 'required' => 'required']) }}--}}

{{--        @if ($errors->has('password_confirmation'))--}}
{{--            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>--}}
{{--        @endif--}}
{{--    </div>--}}

{{--    {{ Form::button('<i class="fa fa-user-plus"></i> '.trans('cortex/auth::common.register'), ['class' => 'btn btn-lg btn-primary btn-block', 'type' => 'submit']) }}--}}

{{--    <div>--}}
{{--        {{ Html::link(route('frontarea.cortex.auth.account.login'), trans('cortex/auth::common.account_login')) }}--}}
{{--        {{ trans('cortex/foundation::common.or') }}--}}
{{--        {{ Html::link(route('frontarea.cortex.auth.account.passwordreset.request'), trans('cortex/auth::common.passwordreset')) }}--}}

{{--    </div>--}}

{{--    {{ Form::close() }}--}}

@endsection
