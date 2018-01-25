{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.active_sessions') }}
@endsection

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            {{ $currentUser->name ?: $currentUser->username }}
                        </div>
                        @if($currentUser->title)
                            <div class="profile-usertitle-job">
                                {{ $currentUser->title }}
                            </div>
                        @endif
                    </div>
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li><a href="{{ route('frontarea.account.settings') }}"><i class="fa fa-cogs"></i>{{ trans('cortex/fort::common.settings') }}</a></li>
                            <li class="active"><a href="{{ route('frontarea.account.sessions') }}"><i class="fa fa-list-alt"></i>{{ trans('cortex/fort::common.sessions') }}</a></li>
                            <li><a href="{{ route('frontarea.account.password') }}"><i class="fa fa-key"></i>{{ trans('cortex/fort::common.password') }}</a></li>
                            <li><a href="{{ route('frontarea.account.twofactor.index') }}"><i class="fa fa-lock"></i>{{ trans('cortex/fort::common.twofactor') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="profile-content">

                    <!-- Tab panes -->
                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane active" id="sessions">

                            @include('cortex/foundation::common.partials.confirm-deletion')

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                        @foreach($currentUser->sessions as $session)

                                            <section class="panel panel-default">

                                                <div class="panel-heading" role="tab" id="heading-{{ $session->id }}">

                                                    <div class="row">

                                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $session->id }}" aria-expanded="false" aria-controls="collapse-{{ $session->id }}">

                                                            <div class="col-md-11 col-sm-11 col-xs-11">

                                                                <span class="label label-info">{{ $session->last_activity->format('F d, Y - h:ia') }} <span style="background-color: #428bca; border-radius: 0 3px 3px 0; margin-right: -6px; padding: 2px 4px 3px;">{{ $session->last_activity->diffForHumans() }}</span></span>
                                                                @if ($session->id === request()->session()->getId())<span class="label label-success">{{ trans('cortex/fort::common.you') }}</span>@endif
                                                                <span class="badge pull-right">{{ $session->ip_address }}</span>

                                                            </div>

                                                        </a>

                                                        <div class="col-md-1 col-sm-1 col-xs-1">
                                                            <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-confirmation" data-modal-action="{{ route('frontarea.account.sessions.delete', ['id' => $session->id]) }}" data-modal-title="{!! trans('cortex/foundation::messages.delete_confirmation_title') !!}" data-modal-body="{!! trans('cortex/foundation::messages.delete_confirmation_body', ['type' => 'session', 'name' => $session->id]) !!}" title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-remove"></i></a>
                                                        </div>

                                                    </div>

                                                </div>

                                                @if($session->user_agent)

                                                    <div id="collapse-{{ $session->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{ $session->id }}">
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

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a class="btn btn-danger" href="#" data-toggle="modal" data-target="#delete-confirmation" data-modal-action="{{ route('frontarea.account.sessions.flush') }}" data-modal-title="{!! trans('cortex/foundation::messages.delete_confirmation_title') !!}" data-modal-body="{!! trans('cortex/foundation::messages.delete_confirmation_body', ['type' => 'sessions', 'name' => 'all']) !!}" title="{{ trans('cortex/foundation::common.delete') }}"><i class="fa fa-remove"></i> {{ trans('cortex/fort::messages.sessions.flush_all_heading') }}</a>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
