{{-- Master Layout --}}
@extends('cortex/foundation::tenantarea.pages.datatable-tab')

@section('tabs')
    <li><a href="{{ route("tenantarea.{$type}.edit", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.details') }}</a></li>
    <li><a href="{{ route("tenantarea.{$type}.logs", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.logs') }}</a></li>
    <li class="active"><a href="#{{ $tab ?? 'activities' }}-tab" data-toggle="tab">{{ trans('cortex/foundation::common.activities') }}</a></li>
@stop
