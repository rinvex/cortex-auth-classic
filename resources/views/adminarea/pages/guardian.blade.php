{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.adminarea') }} » {{ trans('cortex/auth::common.guardians') }} » {{ $guardian->exists ? $guardian->username : trans('cortex/auth::common.create_guardian') }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Adminarea\GuardianFormRequest::class)->selector("#adminarea-guardians-create-form, #adminarea-guardians-{$guardian->getKey()}-update-form") !!}
@endpush

{{-- Main Content --}}
@section('content')

    @if($guardian->exists)
        @include('cortex/foundation::common.partials.confirm-deletion')
    @endif

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="nav-tabs-custom">
                @if($guardian->exists && $currentUser->can('delete', $guardian)) <div class="pull-right"><a href="#" data-toggle="modal" data-target="#delete-confirmation" data-modal-action="{{ route('adminarea.guardians.destroy', ['guardian' => $guardian]) }}" data-modal-title="{!! trans('cortex/foundation::messages.delete_confirmation_title') !!}" data-modal-body="{!! trans('cortex/foundation::messages.delete_confirmation_body', ['type' => 'guardian', 'name' => $guardian->username]) !!}" title="{{ trans('cortex/foundation::common.delete') }}" class="btn btn-default" style="margin: 4px"><i class="fa fa-trash text-danger"></i></a></div> @endif
                {!! Menu::render('adminarea.guardians.tabs', 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="details-tab">

                        @if ($guardian->exists)
                            {{ Form::model($guardian, ['url' => route('adminarea.guardians.update', ['guardian' => $guardian]), 'id' => "adminarea-guardians-{$guardian->getKey()}-update-form", 'method' => 'put', 'files' => true]) }}
                        @else
                            {{ Form::model($guardian, ['url' => route('adminarea.guardians.store'), 'id' => 'adminarea-guardians-create-form', 'files' => true]) }}
                        @endif

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Username --}}
                                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                        {{ Form::label('username', trans('cortex/auth::common.username'), ['class' => 'control-label']) }}
                                        {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.username'), 'required' => 'required']) }}

                                        @if ($errors->has('username'))
                                            <span class="help-block">{{ $errors->first('username') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Email --}}
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        {{ Form::label('email', trans('cortex/auth::common.email'), ['class' => 'control-label']) }}
                                        {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.email'), 'required' => 'required']) }}

                                        @if ($errors->has('email'))
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Active --}}
                                    <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
                                        {{ Form::label('is_active', trans('cortex/auth::common.active'), ['class' => 'control-label']) }}
                                        {{ Form::select('is_active', [1 => trans('cortex/auth::common.yes'), 0 => trans('cortex/auth::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                                        @if ($errors->has('is_active'))
                                            <span class="help-block">{{ $errors->first('is_active') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Tags --}}
                                    <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                                        {{ Form::label('tags[]', trans('cortex/auth::common.tags'), ['class' => 'control-label']) }}
                                        {{ Form::hidden('tags', '') }}
                                        {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'data-width' => '100%', 'data-tags' => 'true']) }}

                                        @if ($errors->has('tags'))
                                            <span class="help-block">{{ $errors->first('tags') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Password --}}
                                    <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                                        {{ Form::label('password', trans('cortex/auth::common.password'), ['class' => 'control-label']) }}
                                        @if ($guardian->exists)
                                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.password')]) }}
                                        @else
                                            {{ Form::password('password', ['class' => 'form-control autogenerate', 'placeholder' => trans('cortex/auth::common.password'), 'required' => 'required']) }}
                                        @endif
                                        <span class="fa fa-key form-control-feedback"></span>

                                        @if ($errors->has('password'))
                                            <span class="help-block">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Password Confirmation --}}
                                    <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        {{ Form::label('password_confirmation', trans('cortex/auth::common.password_confirmation'), ['class' => 'control-label']) }}
                                        @if ($guardian->exists)
                                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('cortex/auth::common.password_confirmation')]) }}
                                        @else
                                            {{ Form::password('password_confirmation', ['class' => 'form-control autogenerate', 'placeholder' => trans('cortex/auth::common.password_confirmation'), 'required' => 'required']) }}
                                        @endif
                                        <span class="fa fa-key form-control-feedback"></span>

                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="pull-right">
                                        {{ Form::button(trans('cortex/auth::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>

                                    @include('cortex/foundation::adminarea.partials.timestamps', ['model' => $guardian])

                                </div>

                            </div>

                        {{ Form::close() }}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
