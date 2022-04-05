{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Main Content --}}
@section('content')
    <section class="container mx-auto my-6">
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
            @include('cortex/auth::frontarea.partials.sidebar')
            <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
                {{ Form::open(['url' => route('frontarea.cortex.auth.account.twofactor.totp.update'), 'id' => 'frontarea-twofactor-totp-form']) }}
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <h3 class="centered text-2xl">
                            @if(Arr::get($twoFactor, 'totp.enabled') || Arr::get($twoFactor, 'phone.enabled'))
                                {!! trans('cortex/auth::twofactor.active') !!}
                            @else
                                {!! trans('cortex/auth::twofactor.inactive') !!}
                            @endif
                        </h3>
                        <p class="text-justify">{{ trans('cortex/auth::twofactor.notice') }}</p>

                        <div class="w-full rounded border border-1 overflow-hidden shadow-lg">
                            <div class="px-6 py-4">
                                <div class="flex justify-between">
                                    <div class="font-bold text-xl mb-2">
                                        {{ trans('cortex/auth::twofactor.totp_head') }}
                                    </div>
                                    <div>
                                        @if(! empty($twoFactor['totp']['enabled']))
                                            <a class="p-2 rounded bg-gray-200 text-black pull-right" href="{{ route('frontarea.cortex.auth.account.twofactor.totp.disable') }}" onclick="event.preventDefault(); var form = document.getElementById('frontarea-twofactor-totp-form'); form.action = '{{ route('frontarea.cortex.auth.account.twofactor.totp.disable') }}'; form.submit();">{{ trans('cortex/auth::common.disable') }}</a>
                                            <a class="p-2 rounded bg-gray-200 text-black pull-right" style="margin-right: 10px" href="{{ route('frontarea.cortex.auth.account.twofactor.totp.enable') }}">{{ trans('cortex/auth::common.settings') }}</a>
                                        @else
                                            <a class="p-2 rounded bg-gray-200 text-black pull-right" href="{{ route('frontarea.cortex.auth.account.twofactor.totp.enable') }}">{{ trans('cortex/auth::common.enable') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 pt-4 pb-2">
                                {!! trans('cortex/auth::twofactor.totp_body') !!}
                            </div>
                        </div>

                        <div class="w-full rounded border border-1 overflow-hidden shadow-lg">
                            <div class="px-6 py-4">
                                <div class="flex justify-between">
                                    <div class="font-bold text-xl mb-2">
                                        {{ trans('cortex/auth::twofactor.phone_head') }}
                                    </div>
                                    <div>
                                        <a class="p-2 rounded bg-gray-200 text-black pull-right" href="{{ route('frontarea.cortex.auth.account.twofactor.phone.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}" onclick="event.preventDefault(); var form = document.getElementById('frontarea-twofactor-totp-form'); form.action = '{{ route('frontarea.cortex.auth.account.twofactor.phone.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}'; form.submit();">{{ trans('cortex/auth::common.'.(! empty($twoFactor['phone']['enabled']) ? 'disable' : 'enable')) }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 pt-4 pb-2">
                                {{ trans('cortex/auth::twofactor.phone_body') }}
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </section>
@endsection
