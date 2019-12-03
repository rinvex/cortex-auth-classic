{{-- Master Layout --}}
@extends('cortex/foundation::managerarea.pages.datatable-index')

{{-- Datatable Filters --}}
@section('datatable-filters')
    {{ Form::open(['id' => 'managerarea-managers-filters-form']) }}
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

    {{ Form::close() }}
    <br />
@endsection

@push('inline-scripts')
    <script>
        window.countries = @json($countries);
    </script>
    <script>
        window.languages = @json($languages);
    </script>
@endpush
