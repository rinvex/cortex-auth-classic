{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Frontarea\AccountPasswordRequest::class)->selector('#frontarea-account-password-form')->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <section class="container mx-auto my-6">
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
            @include('cortex/auth::frontarea.partials.sidebar')
            <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
                {{ Form::model(request()->user(), ['url' => route('frontarea.cortex.auth.account.password.update'), 'id' => 'frontarea-account-password-form']) }}
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="has-feedback{{ $errors->has('old_password') ? ' has-error' : '' }}">
                                {{ Form::label('old_password', trans('cortex/auth::common.old_password'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::password('old_password', ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('old_password')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.old_password')]) }}
                                @if ($errors->has('old_password'))
                                    <span class="help-block">{{ $errors->first('old_password') }}</span>
                                @endif
                            </div>
                            <div class="has-feedback{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                {{ Form::label('new_password', trans('cortex/auth::common.new_password'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::password('new_password', ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('new_password')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.new_password')]) }}
                                @if ($errors->has('new_password'))
                                    <span class="help-block">{{ $errors->first('new_password') }}</span>
                                @endif
                            </div>
                            <div class="has-feedback{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                                {{ Form::label('new_password_confirmation', trans('cortex/auth::common.new_password_confirmation'), ['class' => 'block text-sm font-medium text-gray-700']) }}
                                {{ Form::password('new_password_confirmation', ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('new_password_confirmation')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.new_password_confirmation')]) }}
                                @if ($errors->has('new_password_confirmation'))
                                    <span class="help-block">{{ $errors->first('new_password_confirmation') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        {{ Form::button('<i class="fa fa-save m-1"></i> '.trans('cortex/auth::common.update_password'), ['class' => 'bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500', 'type' => 'submit']) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection
