{{-- Master Layout --}}
@extends('cortex/tenants::managerarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.managerarea') }} » {{ trans('cortex/fort::common.users') }} » {{ $user->username }} » {{ trans('cortex/fort::common.attributes') }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Managerarea\UserAttributesFormRequest::class)->selector("#managerarea-users-create-form, #managerarea-users-{$user->getKey()}-update-attributes-form") !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="nav-tabs-custom">
                {!! Menu::render('managerarea.users.tabs', 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="attributes-tab">
                        @attributes($user)
                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
