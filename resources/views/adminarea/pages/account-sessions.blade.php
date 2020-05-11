{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">

        <section class="content">

            <div class="row profile">
                <div class="col-md-3">
                    @include('cortex/auth::adminarea.partials.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="profile-content">

                        @include('cortex/foundation::common.partials.modal', ['id' => 'delete-confirmation'])

                        <div class="row">

                            <div class="col-md-12">

                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                    @foreach($currentUser->sessions as $session)

                                        <section class="panel panel-default">

                                            <div class="panel-heading" role="tab" id="heading-{{ $session->getKey() }}">

                                                <div class="row">

                                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $session->getKey() }}" aria-expanded="false" aria-controls="collapse-{{ $session->getKey() }}">

                                                        <div class="col-md-11 col-sm-11 col-xs-11">

                                                            <span class="label label-info">{{ $session->last_activity->format('F d, Y - h:ia') }} <span style="background-color: #428bca; border-radius: 0 3px 3px 0; margin-right: -6px; padding: 2px 4px 3px;">{{ $session->last_activity->diffForHumans() }}</span></span>
                                                            @if ($session->getKey() === request()->session()->getId())<span class="label label-success">{{ trans('cortex/auth::common.you') }}</span>@endif
                                                            <span class="badge pull-right">{{ $session->ip_address }}</span>

                                                        </div>

                                                    </a>

                                                    <div class="col-md-1 col-sm-1 col-xs-1">

                                                        @if ($session->getKey() === request()->session()->getId())
                                                            <a class="btn btn-warning btn-xs" href="#" data-toggle="modal" data-target="#delete-confirmation"
                                                               data-modal-action="{{ route('adminarea.account.sessions.flush') }}"
                                                               data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                                               data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/auth::messages.sessions.flush_all_heading') }}</a>"
                                                               data-modal-body="{{ trans('cortex/auth::messages.sessions.delete_other_othres', ['resource' => trans('cortex/auth::common.sessions'), 'identifier' => 'all']) }}"
                                                               title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-minus-square"></i></a>
                                                        @else
                                                            <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-confirmation"
                                                               data-modal-action="{{ route('adminarea.account.sessions.destroy', ['id' => $session->getKey()]) }}"
                                                               data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                                               data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                                               data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/auth::common.session'), 'identifier' => strip_tags($session->getKey())]) }}"
                                                               title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-remove"></i></a>
                                                        @endif

                                                    </div>

                                                </div>

                                            </div>

                                            @if($session->user_agent)

                                                <div id="collapse-{{ $session->getKey() }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{ $session->getKey() }}">
                                                    <div class="panel-body">
                                                        <pre>{{ $session->user_agent }}</pre>
                                                    </div>
                                                </div>

                                            @endif

                                        </section>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>

@endsection
