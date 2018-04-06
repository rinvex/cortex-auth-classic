{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Adminarea\AccountAttributesRequest::class)->selector('#adminarea-account-attributes-form') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">

        <section class="content">

            <div class="row profile">
                <div class="col-md-3">
                    @include('cortex/auth::adminarea.partials.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="profile-content">

                        {{ Form::model($currentUser, ['url' => route('adminarea.account.attributes.update'), 'id' => 'adminarea-account-attributes-form']) }}

                            @attributes($currentUser)

                            @if($currentUser->getEntityAttributes()->isNotEmpty())
                                <div class="row">
                                    <div class="col-md-12 text-center profile-buttons">
                                        {{ Form::button('<i class="fa fa-save"></i> '.trans('cortex/auth::common.update_attributes'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>
                                </div>
                            @endif

                        {{ Form::close() }}

                    </div>
                </div>
            </div>

        </section>

    </div>

@endsection
