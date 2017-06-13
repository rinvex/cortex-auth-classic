{{-- Master Layout --}}
@extends('cortex/foundation::frontend.layouts.default')

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

                                @include('cortex/fort::frontend.partials.confirm-deletion')

                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                            @foreach($currentUser->persistences as $persistence)

                                                <section class="panel panel-default">

                                                    <div class="panel-heading" role="tab" id="heading-{{ $persistence->token }}">

                                                        <div class="row">

                                                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $persistence->token }}" aria-expanded="false" aria-controls="collapse-{{ $persistence->token }}">

                                                                <div class="col-md-11 col-sm-11 col-xs-11">

                                                                    <span class="label label-info">{{ $persistence->created_at->format('F d, Y - h:ia') }}
                                                                        <span style="background-color: #428bca; border-radius: 0 3px 3px 0; margin-right: -6px; padding: 2px 4px 3px;">{{ $persistence->created_at->diffForHumans() }}</span></span>
                                                                    @if ($persistence->token === request()->session()->getId())
                                                                        <span class="label label-success">{{ trans('cortex/fort::common.you') }}</span>@endif
                                                                    <span class="badge pull-right">{{ $persistence->ip }}</span>

                                                                </div>

                                                            </a>

                                                            <div class="col-md-1 col-sm-1 col-xs-1">
                                                                <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete-confirmation" data-item-href="{{ route('frontend.account.sessions.flush', ['token' => $persistence->token]) }}" data-item-type="single"><i class="fa fa-remove"></i></a>
                                                            </div>

                                                        </div>

                                                    </div>

                                                    @if($persistence->agent)

                                                        <div id="collapse-{{ $persistence->token }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{ $persistence->token }}">
                                                            <div class="panel-body">
                                                                <pre>{{ $persistence->agent }}</pre>
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

                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-confirmation" data-item-href="{{ route('frontend.account.sessions.flush') }}" data-item-type="all">
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
