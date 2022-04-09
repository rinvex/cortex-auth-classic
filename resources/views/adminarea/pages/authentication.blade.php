{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.auth')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Adminarea\AuthenticationRequest::class)->selector('#adminarea-cortex-auth-login-form')->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="{{ route('adminarea.home') }}">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">{{ config('app.name') }}</h2>
            </a>
        </div>

        <div class="mt-8 bg-white shadow sm:rounded-lg sm:mx-auto sm:w-full sm:max-w-md">
            <p class="text-center pt-3">{{ trans('cortex/auth::common.account_login') }}</p>
            <div class=" pb-8 px-4 sm:px-10">
                {{ Form::open(['url' => route('adminarea.cortex.auth.account.login.process'), 'id' => 'adminarea-cortex-auth-login-form', 'class' => 'space-y-6']) }}
                <div>
                    <div class="mt-1">
                        {{ Form::text('loginfield', old('loginfield'), ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('loginfield')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.loginfield'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                        @if ($errors->has('loginfield'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('loginfield') }}</p>
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
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent  shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Sign in</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
