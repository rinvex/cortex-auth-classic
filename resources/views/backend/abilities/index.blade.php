{{-- Master Layout --}}
@extends('cortex/fort::backend.common.layout')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.abilities.label') }}
@stop

{{-- Main Content --}}
@section('content')

    <style>
        td {
            vertical-align: middle !important;
        }
    </style>

    <div class="container">

        @include('cortex/fort::frontend.alerts.success')
        @include('cortex/fort::frontend.alerts.warning')
        @include('cortex/fort::frontend.alerts.error')
        @include('cortex/fort::backend.common.confirm-modal', ['type' => 'ability'])

        <section class="panel panel-default">

            {{-- Heading --}}
            <header class="panel-heading">
                <h4>
                    {{ trans('cortex/fort::common.abilities.label') }}
                    @can('create-abilities')
                        <span class="pull-right" style="margin-top: -7px">
                            <a href="{{ route('backend.abilities.create') }}" class="btn btn-default"><i class="fa fa-plus"></i></a>
                        </span>
                    @endcan
                </h4>
            </header>

            {{-- Data --}}
            <div class="panel-body">

                <div class="table-responsive">

                    <table class="table table-hover" style="margin-bottom: 0">

                        <thead>
                            <tr>
                                <th style="width: 30%">{{ trans('cortex/fort::common.name') }}</th>
                                <th style="width: 40%">{{ trans('cortex/fort::common.description') }}</th>
                                <th style="width: 15%">{{ trans('cortex/fort::common.created_at') }}</th>
                                <th style="width: 15%">{{ trans('cortex/fort::common.updated_at') }}</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($abilities as $ability)

                                <tr>
                                    <td>
                                        @can('update-abilities', $ability) <a href="{{ route('backend.abilities.edit', ['ability' => $ability]) }}"> @endcan
                                            <strong>{{ $ability->name }}</strong> <small>({{ $ability->action }})</small>
                                            <div class="small ">{{ $ability->policy }}</div>
                                        @can('update-abilities', $ability) </a> @endcan
                                    </td>

                                    <td>
                                        {{ $ability->description }}
                                    </td>

                                    <td class="small">
                                        @if($ability->created_at)
                                            <div>
                                                {{ trans('cortex/fort::common.created_at') }}: <time datetime="{{ $ability->created_at }}">{{ $ability->created_at->format('Y-m-d') }}</time>
                                            </div>
                                        @endif
                                        @if($ability->updated_at)
                                            <div>
                                                {{ trans('cortex/fort::common.updated_at') }}: <time datetime="{{ $ability->updated_at }}">{{ $ability->updated_at->format('Y-m-d') }}</time>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-right">
                                        @can('update-abilities', $ability) <a href="{{ route('backend.abilities.edit', ['ability' => $ability]) }}" class="btn btn-xs btn-default"><i class="fa fa-pencil text-primary"></i></a> @endcan
                                        @can('delete-abilities', $ability) <a href="#" class="btn btn-xs btn-default" data-toggle="modal" data-target="#delete-confirmation" data-item-href="{{ route('backend.abilities.delete', ['ability' => $ability]) }}" data-item-name="{{ $ability->slug }}"><i class="fa fa-trash-o text-danger"></i></a> @endcan
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- Pagination --}}
            @include('cortex/fort::backend.common.pagination', ['resource' => $abilities])

        </section>

    </div>

@endsection
