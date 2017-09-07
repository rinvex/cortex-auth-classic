{{-- Master Layout --}}
@extends('cortex/foundation::memberarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.active_sessions') }}
@stop

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">

            <div class="row">

                <div class="col-md-6 col-md-offset-3">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#sessions-tab" data-toggle="tab">{{ trans('cortex/fort::common.sessions') }}</a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="sessions-tab">

                                @include('cortex/fort::memberarea.partials.confirm-deletion')

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
                                                                <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-confirmation" data-item-href="{{ route('memberarea.account.sessions.flush', ['id' => $session->id]) }}" data-item-type="single"><i class="fa fa-remove"></i></a>
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

                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-confirmation" data-item-href="{{ route('memberarea.account.sessions.flush') }}" data-item-type="all">
                                            <i class="fa fa-remove"></i> {{ trans('cortex/fort::messages.sessions.flush_all_heading') }}
                                        </button>

                                    </div>
                                </div>

                            </div>
                            <!-- /.tab-pane -->

                        </div>
                        <!-- /.tab-content -->

                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>

            </div>

        </section>

    </div>

@endsection
