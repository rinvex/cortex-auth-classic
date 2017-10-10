{{-- Master Layout --}}
@extends('cortex/foundation::tenantarea.pages.datatable-tab')

@section('tabs')
    <li><a href="{{ route("tenantarea.{$type}.edit", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.details') }}</a></li>
    <li class="active"><a href="#{{ $tab }}-tab" data-toggle="tab">{{ trans('cortex/foundation::common.'.$tab) }}</a></li>
    @if(method_exists($resource, 'causedActivity')) <li><a href="{{ route("tenantarea.{$type}.activities", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.activities') }}</a></li> @endif
    <li style="float: right; padding: 5px"><select class="form-control dataTableBuilderLengthChanger" aria-controls="{{ $id }}-table"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select></li>
@stop
