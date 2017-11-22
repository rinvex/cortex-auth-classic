{{-- Master Layout --}}
@extends('cortex/foundation::tenantarea.pages.datatable-tab')

@section('tabs')
    <li><a href="{{ route("tenantarea.{$type}.edit", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.details') }}</a></li>
    <li class="active"><a href="#{{ $tab }}-tab" data-toggle="tab">{{ trans('cortex/foundation::common.'.$tab) }}</a></li>
    @if(method_exists($resource, 'causedActivity')) <li><a href="{{ route("tenantarea.{$type}.activities", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.activities') }}</a></li> @endif
@stop
