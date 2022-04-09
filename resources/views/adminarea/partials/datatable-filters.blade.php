{{ Form::open(['id' => "adminarea-cortex-auth-{$resource}-filters-form"]) }}

    <div class="grid grid-cols-4 gap-6">

        {{-- Country Code --}}
        <div class="{{ $errors->has('country_code') ? ' has-error' : '' }}">
            {{ Form::label('country_code', trans('cortex/auth::common.country'), ['class' => 'font-bold']) }}
            {{ Form::hidden('country_code', '', ['class' => 'skip-validation', 'id' => 'country_code_hidden']) }}
            {{ Form::select('country_code', [], null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 select2', 'placeholder' => trans('cortex/auth::common.country'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}
        </div>

        {{-- Language code --}}
        <div class="{{ $errors->has('language_code') ? ' has-error' : '' }}">
            {{ Form::label('language_code', trans('cortex/auth::common.language'), ['class' => 'font-bold']) }}
            {{ Form::hidden('language_code', '', ['class' => 'skip-validation', 'id' => 'language_code_hidden']) }}
            {{ Form::select('language_code', $languages, null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 select2', 'placeholder' => trans('cortex/auth::common.language'), 'data-allow-clear' => 'true', 'data-width' => '100%']) }}
        </div>

        {{-- Gender --}}
        <div class="{{ $errors->has('gender') ? ' has-error' : '' }}">
            {{ Form::label('gender', trans('cortex/auth::common.gender'), ['class' => 'font-bold']) }}
            {{ Form::hidden('gender', '', ['class' => 'skip-validation', 'id' => 'gender_hidden']) }}
            {{ Form::select('gender', $genders, null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 select2', 'placeholder' => trans('cortex/auth::common.select_gender'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

            @if ($errors->has('gender'))
                <span class="help-block">{{ $errors->first('gender') }}</span>
            @endif
        </div>

        {{-- Tags --}}
        <div class="{{ $errors->has('tags') ? ' has-error' : '' }}">
            {{ Form::label('tags[]', trans('cortex/auth::common.tags'), ['class' => 'font-bold']) }}
            {{ Form::hidden('tags', '', ['class' => 'skip-validation']) }}
            {{ Form::select('tags[]', $tags, null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 select2', 'multiple' => 'multiple', 'data-width' => '100%', 'data-tags' => 'true']) }}

            @if ($errors->has('tags'))
                <span class="help-block">{{ $errors->first('tags') }}</span>
            @endif
        </div>

        {{-- Role --}}
        <div class="{{ $errors->has('role_id') ? ' has-error' : '' }}">
            {{ Form::label('role_id', trans('cortex/auth::common.role'), ['class' => 'font-bold']) }}
            {{ Form::hidden('role_id', '', ['class' => 'skip-validation', 'id' => 'role_id_hidden']) }}
            {{ Form::select('role_id', $roles, null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 select2', 'placeholder' => trans('cortex/auth::common.select_role'), 'data-allow-clear' => 'true', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%']) }}

            @if ($errors->has('role_id'))
                <span class="help-block">{{ $errors->first('role_id') }}</span>
            @endif
        </div>

        {{--  Created at from --}}
        <div class="{{ $errors->has('created_at_from') ? ' has-error' : '' }}">
            {{ Form::label('created_at_from', trans('cortex/foundation::common.created_at_from'), ['class' => 'font-bold']) }}
            {{ Form::date('created_at_from', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300', 'data-locale' => '{"format": "YYYY-MM-DD"}', 'data-single-date-picker' => 'true', 'data-show-dropdowns' => 'true', 'data-auto-apply' => 'true', 'data-min-date' => '1900-01-01', 'data-start-date' => '1900-01-01']) }}

            @if ($errors->has('created_at_from'))
                <span class="help-block">{{ $errors->first('created_at_from') }}</span>
            @endif
        </div>

        {{--  Created at to --}}
        <div class="{{ $errors->has('created_at_to') ? ' has-error' : '' }}">
            {{ Form::label('created_at_to', trans('cortex/foundation::common.created_at_to'), ['class' => 'font-bold']) }}
            {{ Form::date('created_at_to', null, ['class' => 'appearance-none block w-full px-3 py-2 border  shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300', 'data-locale' => '{"format": "YYYY-MM-DD"}', 'data-single-date-picker' => 'true', 'data-show-dropdowns' => 'true', 'data-auto-apply' => 'true', 'data-min-date' => '1900-01-01']) }}

            @if ($errors->has('created_at_to'))
                <span class="help-block">{{ $errors->first('created_at_to') }}</span>
            @endif
        </div>
    </div>

{{ Form::close() }}

<br />
