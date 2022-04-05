{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Scripts --}}
@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationSendRequest::class)->selector('#frontarea-verification-phone-request-form')->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">{{ trans('cortex/auth::common.account_verification_phone') }}</h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                {{ Form::open(['url' => route('frontarea.cortex.auth.account.verification.phone.send'), 'id' => 'frontarea-verification-phone-request-form', 'class' => 'space-y-6']) }}
                <div>
                    <div class="mt-1">
                        @if (request()->user())
                            {{ Form::tel('phone_input', old('phone', request()->user()->phone), ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus', 'disabled' => 'disabled']) }}
                        @else
                            {{ Form::tel('phone_input', old('phone'), ['class' => 'appearance-none block w-full px-3 py-2 border shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'.($errors->has('token')?' border-red-500':' border-gray-300'), 'placeholder' => trans('cortex/auth::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                        @endif

                        <div class="input-group-btn" data-toggle="buttons">
                            <label for="sms" class="btn btn-default @if(! old('method') || old('method') === 'sms') active @endif">
                                <input style="margin: 0 !important;" id="sms" name="method" type="radio" value="sms" autocomplete="off" @if(! old('method') || old('method') == 'sms') checked @endif> {{ trans('cortex/auth::common.sms') }}
                            </label>
                            <label for="call" class="btn btn-default @if(old('method') === 'call') active @endif">
                                <input style="margin: 0 !important;" id="call" name="method" type="radio" value="call" autocomplete="off" @if(old('method') === 'call') checked @endif> {{ trans('cortex/auth::common.call') }}
                            </label>
                        </div>
                    </div>
                    <span class="help-block hide">{{ trans('cortex/foundation::messages.invalid_phone') }}</span>
                    @if ($errors->has('phone'))
                        <p class="text-red-500 text-xs italic">{{ $errors->first('phone') }}</p>
                    @endif
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">{{ trans('cortex/auth::common.passwordreset_request') }}</button>
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
                            {{ Html::link(route('frontarea.cortex.auth.account.register.member'), trans('cortex/auth::common.account_register')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
