{{ Form::open(['id' => "adminarea-cortex-auth-{$resource}-filters-form"]) }}

    <div class="row">

        {{-- Country Code --}}
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
                {{ Form::label('country_code', trans('cortex/auth::common.country'), ['class' => 'control-label']) }}
                {{ Form::hidden('country_code', '', ['class' => 'skip-validation', 'id' => 'country_code_hidden']) }}
                {{ Form::select('country_code', [], null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.country'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}
            </div>
        </div>

        {{-- Language code --}}
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('language_code') ? ' has-error' : '' }}">
                {{ Form::label('language_code', trans('cortex/auth::common.language'), ['class' => 'control-label']) }}
                {{ Form::hidden('language_code', '', ['class' => 'skip-validation', 'id' => 'language_code_hidden']) }}
                {{ Form::select('language_code', $languages, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.language'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}
            </div>
        </div>

        {{-- Gender --}}
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                {{ Form::label('gender', trans('cortex/auth::common.gender'), ['class' => 'control-label']) }}
                {{ Form::hidden('gender', '', ['class' => 'skip-validation', 'id' => 'gender_hidden']) }}
                {{ Form::select('gender', $genders, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_gender'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                @if ($errors->has('gender'))
                    <span class="help-block">{{ $errors->first('gender') }}</span>
                @endif
            </div>
        </div>

        {{-- Tags --}}
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
                {{ Form::label('tags[]', trans('cortex/auth::common.tags'), ['class' => 'control-label']) }}
                {{ Form::hidden('tags', '', ['class' => 'skip-validation']) }}
                {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'data-width' => '100%', 'data-tags' => 'true']) }}

                @if ($errors->has('tags'))
                    <span class="help-block">{{ $errors->first('tags') }}</span>
                @endif
            </div>
        </div>

    </div>


    <div class="row">

        <div class="col-md-3">
            {{-- Role --}}
            <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}">
                {{ Form::label('role_id', trans('cortex/auth::common.role'), ['class' => 'control-label']) }}
                {{ Form::hidden('role_id', '', ['class' => 'skip-validation', 'id' => 'role_id_hidden']) }}
                {{ Form::select('role_id', $roles, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/auth::common.select_role'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

                @if ($errors->has('role_id'))
                    <span class="help-block">{{ $errors->first('role_id') }}</span>
                @endif
            </div>

        </div>

        <div class="col-md-3">
            {{--  Created at from --}}
            <div class="form-group{{ $errors->has('created_at_from') ? ' has-error' : '' }}">
                {{ Form::label('created_at_from', trans('cortex/foundation::common.created_at_from'), ['class' => 'control-label']) }}
                {{ Form::text('created_at_from', null, ['class' => 'form-control daterangepicker', 'data-locale' => '{"format": "YYYY-MM-DD"}', 'data-single-date-picker' => 'true', 'data-show-dropdowns' => 'true', 'data-auto-apply' => 'true', 'data-min-date' => '1900-01-01', 'data-start-date' => '1900-01-01']) }}

                @if ($errors->has('created_at_from'))
                    <span class="help-block">{{ $errors->first('created_at_from') }}</span>
                @endif
            </div>
        </div>

        <div class="col-md-3">
            {{--  Created at to --}}
            <div class="form-group{{ $errors->has('created_at_to') ? ' has-error' : '' }}">
                {{ Form::label('created_at_to', trans('cortex/foundation::common.created_at_to'), ['class' => 'control-label']) }}
                {{ Form::text('created_at_to', null, ['class' => 'form-control daterangepicker', 'data-locale' => '{"format": "YYYY-MM-DD"}', 'data-single-date-picker' => 'true', 'data-show-dropdowns' => 'true', 'data-auto-apply' => 'true', 'data-min-date' => '1900-01-01']) }}

                @if ($errors->has('created_at_to'))
                    <span class="help-block">{{ $errors->first('created_at_to') }}</span>
                @endif
            </div>
        </div>

    </div>

{{ Form::close() }}

<br />
