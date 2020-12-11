{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Adminarea\ManagerAttributesFormRequest::class)->selector("#adminarea-cortex-auth-managers-create-form, #adminarea-cortex-auth-managers-{$manager->getRouteKey()}-update-attributes-form")->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="nav-tabs-custom">
                {!! Menu::render('adminarea.cortex.auth.managers.tabs', 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="attributes-tab">
                        @attributes($manager)
                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
