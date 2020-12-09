{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Auth\Http\Requests\Adminarea\AdminAttributesFormRequest::class)->selector("#adminarea-cortex-auth-admins-create-form, #adminarea-cortex-auth-admins-{$admin->getRouteKey()}-update-attributes-form")->ignore('.skip-validation') !!}
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
                {!! Menu::render('adminarea.cortex.auth.admins.tabs', 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="attributes-tab">

                        {{--{{ Form::model($admin, ['url' => route('adminarea.cortex.auth.admins.attributes', ['admin' => $admin]), 'id' => "adminarea-cortex-auth-admins-{$admin->getRouteKey()}-attributes-form", 'method' => 'put', 'files' => true]) }}--}}

                            {{--@attributes($admin)--}}

                            {{--<div class="row">--}}
                                {{--<div class="col-md-12">--}}

                                    {{--<div class="pull-right">--}}
                                        {{--{{ Form::button(trans('cortex/auth::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}--}}
                                    {{--</div>--}}

                                    {{--@include('cortex/foundation::adminarea.partials.timestamps', ['model' => $admin])--}}

                                {{--</div>--}}

                            {{--</div>--}}

                        {{--{{ Form::close() }}--}}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
