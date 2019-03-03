{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Adminarea\AbilityFormProcessRequest::class)->selector("#adminarea-abilities-create-form, #adminarea-abilities-{$ability->getRouteKey()}-update-form")->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    @includeWhen($ability->exists, 'cortex/foundation::common.partials.modal', ['id' => 'delete-confirmation'])

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="nav-tabs-custom">
                @if($ability->exists && $currentUser->can('delete', $ability))
                    <div class="pull-right">
                        <a href="#" data-toggle="modal" data-target="#delete-confirmation"
                           data-modal-action="{{ route('adminarea.abilities.destroy', ['ability' => $ability]) }}"
                           data-modal-title="{!! trans('cortex/foundation::messages.delete_confirmation_title') !!}"
                           data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                           data-modal-body="{!! trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/auth::common.ability'), 'identifier' => $ability->title]) !!}"
                           title="{{ trans('cortex/foundation::common.delete') }}" class="btn btn-default" style="margin: 4px"><i class="fa fa-trash text-danger"></i>
                        </a>
                    </div>
                @endif
                {!! Menu::render('adminarea.abilities.tabs', 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="details-tab">

                        @if ($ability->exists)
                            {{ Form::model($ability, ['url' => route('adminarea.abilities.update', ['ability' => $ability]), 'method' => 'put', 'id' => "adminarea-abilities-{$ability->getRouteKey()}-update-form"]) }}
                        @else
                            {{ Form::model($ability, ['url' => route('adminarea.abilities.store'), 'id' => 'adminarea-abilities-create-form']) }}
                        @endif

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Title --}}
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        {{ Form::label('title', trans('cortex/auth::common.title'), ['class' => 'control-label']) }}
                                        {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.title'), 'data-slugify' => '[name="name"]', 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                        @if ($errors->has('title'))
                                            <span class="help-block">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Name --}}
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        {{ Form::label('name', trans('cortex/auth::common.name'), ['class' => 'control-label']) }}
                                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.name'), 'required' => 'required']) }}

                                        @if ($errors->has('name'))
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Only Owned --}}
                                    <div class="form-group{{ $errors->has('only_owned') ? ' has-error' : '' }}">
                                        {{ Form::label('only_owned', trans('cortex/auth::common.only_owned'), ['class' => 'control-label']) }}
                                        {{ Form::select('only_owned', [1 => trans('cortex/auth::common.yes'), 0 => trans('cortex/auth::common.no')], $ability->exists ? null : 0, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                                        @if ($errors->has('only_owned'))
                                            <span class="help-block">{{ $errors->first('only_owned') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Entity Type --}}
                                    <div class="form-group{{ $errors->has('entity_type') ? ' has-error' : '' }}">
                                        {{ Form::label('entity_type', trans('cortex/auth::common.entity_type'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('entity_type', '', ['class' => 'skip-validation']) }}
                                        {{ Form::select('entity_type', $entityTypes, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/attributes::common.select_entity_type'), 'data-tags' => 'true', 'required' => 'required', 'data-width' => '100%']) }}

                                        @if ($errors->has('entity_type'))
                                            <span class="help-block">{{ $errors->first('entity_type') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Entity Id --}}
                                    <div class="form-group{{ $errors->has('entity_id') ? ' has-error' : '' }}">
                                        {{ Form::label('entity_id', trans('cortex/auth::common.entity_id'), ['class' => 'control-label']) }}
                                        {{ Form::number('entity_id', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.entity_id')]) }}

                                        @if ($errors->has('entity_id'))
                                            <span class="help-block">{{ $errors->first('entity_id') }}</span>
                                        @endif
                                    </div>

                                </div>

                                @can('grant', \Cortex\Auth\Models\Ability::class)
                                    <div class="col-md-4">

                                        {{-- Roles --}}
                                        <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                                            {{ Form::label('roles[]', trans('cortex/auth::common.roles'), ['class' => 'control-label']) }}
                                            {{ Form::hidden('roles', '', ['class' => 'skip-validation']) }}
                                            {{ Form::select('roles[]', $roles, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_roles'), 'multiple' => 'multiple', 'data-close-on-select' => 'false', 'data-width' => '100%']) }}

                                            @if ($errors->has('roles'))
                                                <span class="help-block">{{ $errors->first('roles') }}</span>
                                            @endif
                                        </div>

                                    </div>
                                @endcan

                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="pull-right">
                                        {{ Form::button(trans('cortex/auth::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>

                                    @include('cortex/foundation::adminarea.partials.timestamps', ['model' => $ability])

                                </div>
                            </div>

                        {{ Form::close() }}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
