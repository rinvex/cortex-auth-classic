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
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        @foreach(request()->user()->sessions as $session)
                            <div class="rounded border border-1 overflow-hidden shadow-lg">
                                <div class="px-6 py-4">
                                    <div class="font-bold text-xl mb-2">
                                        <div class="grid grid-cols-5">
                                            <div class="col-span-3">
                                                <span class="rounded p-1 pr-0 text-md text-white bg-sky-400 ">{{ $session->last_activity->format('F d, Y - h:ia') }}
                                                    <span class="rounded p-1 bg-sky-600">
                                                        {{ $session->last_activity->diffForHumans() }}
                                                    </span>
                                                </span>
                                                @if ($session->getKey() === request()->session()->getId())<span class="rounded p-1 text-white bg-green-700">{{ trans('cortex/auth::common.you') }}</span>@endif
                                            </div>
                                            <div>
                                                <span class="rounded-full p-1 text-white bg-black text-center">{{ $session->ip_address }}</span>
                                            </div>
                                            @if ($session->getKey() === request()->session()->getId())
                                                <a class="text-right text-orange-400" href="#" data-toggle="modal" data-target="#delete-confirmation"
                                                   data-modal-action="{{ route('frontarea.cortex.auth.account.sessions.flush') }}"
                                                   data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                                   data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/auth::messages.sessions.flush_all_heading') }}</a>"
                                                   data-modal-body="{{ trans('cortex/auth::messages.sessions.delete_other_othres', ['resource' => trans('cortex/auth::common.sessions'), 'identifier' => 'all']) }}"
                                                   title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-minus-square"></i></a>
                                            @else
                                                <a href="#" class="text-right text-red-700" data-toggle="modal" data-target="#delete-confirmation"
                                                   data-modal-action="{{ route('frontarea.cortex.auth.account.sessions.destroy', ['id' => $session->getKey()]) }}"
                                                   data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                                   data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                                   data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/auth::common.session'), 'identifier' => $session->getRouteKey()]) }}"
                                                   title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-remove"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                    @if($session->user_agent)
                                        <p class="text-gray-700 text-base">
                                            <pre>{{ $session->user_agent }}</pre>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
