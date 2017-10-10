{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.pages.datatable-logs')

@section('tabs')
    <li><a href="{{ route("adminarea.{$type}.edit", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.details') }}</a></li>
    <li><a href="{{ route("adminarea.{$type}.logs", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.logs') }}</a></li>
    <li class="active"><a href="#{{ $tab ?? 'activities' }}-tab" data-toggle="tab">{{ trans('cortex/foundation::common.activities') }}</a></li>
    <li style="float: right; padding: 5px"><select class="form-control dataTableBuilderLengthChanger" aria-controls="{{ $id }}-table"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select></li>
@stop
