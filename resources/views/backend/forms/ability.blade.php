{{-- Master Layout --}}
@extends('cortex/foundation::backend.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.backend') }} » {{ trans('cortex/fort::common.abilities') }} » {{ $ability->exists ? $ability->slug : trans('cortex/fort::common.create_ability') }}
@stop

@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Backend\AbilityFormRequest::class)->selector('#backend-abilities-save') !!}
@endpush

{{-- Main Content --}}
@section('content')

    @if($ability->exists)
        @include('cortex/foundation::backend.partials.confirm-deletion', ['type' => 'ability'])
    @endif

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ $ability->exists ? $ability->name : trans('cortex/fort::common.create_ability') }}</h1>
            <!-- Breadcrumbs -->
            {{ Breadcrumbs::render() }}
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#details-tab" data-toggle="tab">{{ trans('cortex/fort::common.details') }}</a></li>
                    @if($ability->exists) <li><a href="{{ route('backend.abilities.logs', ['ability' => $ability]) }}">{{ trans('cortex/fort::common.logs') }}</a></li> @endif
                    @if($ability->exists && $currentUser->can('delete-abilities', $ability)) <li class="pull-right"><a href="#" data-toggle="modal" data-target="#delete-confirmation" data-item-href="{{ route('backend.abilities.delete', ['ability' => $ability]) }}" data-item-name="{{ $ability->slug }}"><i class="fa fa-trash text-danger"></i></a></li> @endif
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="details-tab">

                        @if ($ability->exists)
                            {{ Form::model($ability, ['url' => route('backend.abilities.update', ['ability' => $ability]), 'method' => 'put', 'id' => 'backend-abilities-save']) }}
                        @else
                            {{ Form::model($ability, ['url' => route('backend.abilities.store'), 'id' => 'backend-abilities-save']) }}
                        @endif

                            <div class="row">
                                <div class="col-md-6">

                                    {{-- Name --}}
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        {{ Form::label('name', trans('cortex/fort::common.name'), ['class' => 'control-label']) }}
                                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.name'), 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                        @if ($errors->has('name'))
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    {{-- Policy --}}
                                    <div class="form-group{{ $errors->has('policy') ? ' has-error' : '' }}">
                                        {{ Form::label('policy', trans('cortex/fort::common.policy'), ['class' => 'control-label']) }}
                                        {{ Form::text('policy', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.policy')]) }}

                                        @if ($errors->has('policy'))
                                            <span class="help-block">{{ $errors->first('policy') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">

                                    {{-- Action --}}
                                    <div class="form-group{{ $errors->has('action') ? ' has-error' : '' }}">
                                        {{ Form::label('action', trans('cortex/fort::common.action'), ['class' => 'control-label']) }}
                                        {{ Form::text('action', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.action'), 'required' => 'required']) }}

                                        @if ($errors->has('action'))
                                            <span class="help-block">{{ $errors->first('action') }}</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    {{-- Resource --}}
                                    <div class="form-group{{ $errors->has('resource') ? ' has-error' : '' }}">
                                        {{ Form::label('resource', trans('cortex/fort::common.resource'), ['class' => 'control-label']) }}
                                        {{ Form::text('resource', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.resource'), 'required' => 'required']) }}

                                        @if ($errors->has('resource'))
                                            <span class="help-block">{{ $errors->first('resource') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                @can('grant-abilities')
                                    <div class="col-md-12">

                                        {{-- Roles --}}
                                        <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                                            {{ Form::label('roles[]', trans('cortex/fort::common.roles'), ['class' => 'control-label']) }}
                                            {{ Form::select('roles[]', $roles, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_roles'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('roles'))
                                                <span class="help-block">{{ $errors->first('roles') }}</span>
                                            @endif
                                        </div>

                                    </div>
                                @endcan

                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    {{-- Description --}}
                                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                        {{ Form::label('description', trans('cortex/fort::common.description'), ['class' => 'control-label']) }}
                                        {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.description'), 'rows' => 3]) }}

                                        @if ($errors->has('description'))
                                            <span class="help-block">{{ $errors->first('description') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="pull-right">
                                        {{ Form::button(trans('cortex/fort::common.reset'), ['class' => 'btn btn-default btn-flat', 'type' => 'reset']) }}
                                        {{ Form::button(trans('cortex/fort::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>

                                    @include('cortex/foundation::backend.partials.timestamps', ['model' => $ability])

                                </div>
                            </div>

                        {{ Form::close() }}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
