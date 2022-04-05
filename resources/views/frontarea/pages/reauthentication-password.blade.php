{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@section('body-attributes')class="auth-page"@endsection

{{-- Main Content --}}
@section('content')

    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">{{ trans('cortex/auth::common.reauthentication.password') }}</h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                {{ Form::open(['url' => route('frontarea.cortex.auth.account.reauthentication.password.process'), 'id' => 'frontarea-reauthentication-form', 'class' => 'space-y-6']) }}

                <div>
                    <div class="mt-1">
                        {{ Form::password('password', ['class' => 'appearance-none block w-full px-3 py-2 border shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('password')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.password'), 'required' => 'required']) }}
                        @if ($errors->has('password'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ trans('cortex/auth::common.login') }}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
