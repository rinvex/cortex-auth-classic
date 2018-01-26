{{-- Master Layout --}}
@extends('cortex/tenants::tenantarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.account_attributes') }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Fort\Http\Requests\Tenantarea\AccountAttributesRequest::class)->selector('#tenantarea-account-attributes-form') !!}
@endpush

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            {{ $currentUser->name ?: $currentUser->username }}
                        </div>
                        @if($currentUser->title)
                            <div class="profile-usertitle-job">
                                {{ $currentUser->title }}
                            </div>
                        @endif
                    </div>
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li><a href="{{ route('tenantarea.account.settings') }}"><i class="fa fa-cogs"></i>{{ trans('cortex/fort::common.settings') }}</a></li>
                            <li class="active"><a href="{{ route('tenantarea.account.attributes') }}"><i class="fa fa-leaf"></i>{{ trans('cortex/fort::common.attributes') }}</a></li>
                            <li><a href="{{ route('tenantarea.account.sessions') }}"><i class="fa fa-list-alt"></i>{{ trans('cortex/fort::common.sessions') }}</a></li>
                            <li><a href="{{ route('tenantarea.account.password') }}"><i class="fa fa-key"></i>{{ trans('cortex/fort::common.password') }}</a></li>
                            <li><a href="{{ route('tenantarea.account.twofactor.index') }}"><i class="fa fa-lock"></i>{{ trans('cortex/fort::common.twofactor') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="profile-content">

                    {{ Form::model($currentUser, ['url' => route('tenantarea.account.attributes.update'), 'id' => 'tenantarea-account-attributes-form']) }}

                        @attributes($currentUser)

                        @if($currentUser->getEntityAttributes()->isNotEmpty())
                            <div class="row">
                                <div class="col-md-12 text-center profile-buttons">
                                    {{ Form::button('<i class="fa fa-save"></i> '.trans('cortex/fort::common.update_attributes'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                </div>
                            </div>
                        @endif

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>

@endsection
