{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.adminarea') }} » {{ trans('cortex/fort::common.roles') }} » {{ $role->exists ? $role->name : trans('cortex/fort::common.create_role') }}
@stop

@push('scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Adminarea\RoleFormRequest::class)->selector('#adminarea-roles-save') !!}
@endpush

{{-- Main Content --}}
@section('content')

    @if($role->exists)
        @include('cortex/foundation::common.partials.confirm-deletion', ['type' => 'role'])
    @endif

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ $role->exists ? $role->name : trans('cortex/fort::common.create_role') }}</h1>
            <!-- Breadcrumbs -->
            {{ Breadcrumbs::render() }}
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#details-tab" data-toggle="tab">{{ trans('cortex/fort::common.details') }}</a></li>
                    @if($role->exists) <li><a href="{{ route('adminarea.roles.logs', ['role' => $role]) }}">{{ trans('cortex/fort::common.logs') }}</a></li> @endif
                    @if($role->exists && $currentUser->can('delete-roles', $role)) <li class="pull-right"><a href="#" data-toggle="modal" data-target="#delete-confirmation" data-item-href="{{ route('adminarea.roles.delete', ['role' => $role]) }}" data-item-name="{{ $role->slug }}"><i class="fa fa-trash text-danger"></i></a></li> @endif
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="details-tab">

                        @if ($role->exists)
                            {{ Form::model($role, ['url' => route('adminarea.roles.update', ['role' => $role]), 'method' => 'put', 'id' => 'adminarea-roles-save']) }}
                        @else
                            {{ Form::model($role, ['url' => route('adminarea.roles.store'), 'id' => 'adminarea-roles-save']) }}
                        @endif

                            <div class="row">
                                <div class="col-md-8">

                                    {{-- Name --}}
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        {{ Form::label('name', trans('cortex/fort::common.name'), ['class' => 'control-label']) }}
                                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.name'), 'data-slugify' => '#slug', 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                        @if ($errors->has('name'))
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    {{-- Slug --}}
                                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                        {{ Form::label('slug', trans('cortex/fort::common.slug'), ['class' => 'control-label']) }}
                                        {{ Form::text('slug', null, ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.slug'), 'required' => 'required']) }}

                                        @if ($errors->has('slug'))
                                            <span class="help-block">{{ $errors->first('slug') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                @can('grant-abilities')
                                    <div class="col-md-12">

                                        {{-- Abilities --}}
                                        <div class="form-group{{ $errors->has('abilities') ? ' has-error' : '' }}">
                                            {{ Form::label('abilities[]', trans('cortex/fort::common.abilities'), ['class' => 'control-label']) }}
                                            {{ Form::select('abilities[]', $abilities, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/fort::common.select_abilities'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('abilities'))
                                                <span class="help-block">{{ $errors->first('abilities') }}</span>
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
                                        {{ Form::button(trans('cortex/fort::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>

                                    @include('cortex/foundation::adminarea.partials.timestamps', ['model' => $role])

                                </div>
                            </div>

                        {{ Form::close() }}

                    </div>
                </div>
            </div>

        </section>

    </div>

@endsection
