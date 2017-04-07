{{-- Master Layout --}}
@extends('cortex/fort::frontend.common.layout')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/fort::common.verification_phone_request') }}
@stop

{{-- Main Content --}}
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <section class="panel panel-default">
                    <header class="panel-heading">{{ trans('cortex/fort::common.verification_phone_request') }}</header>

                    <div class="panel-body">
                        {{ Form::open(['url' => route('frontend.verification.phone.send'), 'class' => 'form-horizontal']) }}

                            @include('cortex/fort::frontend.alerts.success')
                            @include('cortex/fort::frontend.alerts.warning')
                            @include('cortex/fort::frontend.alerts.error')

                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                {{ Form::label('phone', trans('cortex/fort::common.phone'), ['class' => 'col-md-4 control-label']) }}

                                <div class="col-md-6">
                                    @if (auth()->user())
                                        {{ Form::number('phone', old('phone', $currentUser->phone), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus', 'disabled' => 'disabled']) }}
                                        {{ Form::hidden('phone', old('phone', $currentUser->phone)) }}
                                    @else
                                        {{ Form::number('phone', old('phone'), ['class' => 'form-control', 'placeholder' => trans('cortex/fort::common.phone'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                                    @endif

                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('method') ? ' has-error' : '' }}">
                                {{ Form::label('phone', trans('cortex/fort::common.verification_method'), ['class' => 'col-md-4 control-label']) }}

                                <div class="col-md-6">

                                    <div class="btn-group" data-toggle="buttons">
                                        <label for="sms" class="btn btn-default active">
                                            <input id="sms" name="method" type="radio" value="sms" autocomplete="off" checked> {{ trans('cortex/fort::common.sms') }}
                                        </label>
                                        <label for="call" class="btn btn-default">
                                            <input id="call" name="method" type="radio" value="call" autocomplete="off"> {{ trans('cortex/fort::common.call') }}
                                        </label>
                                    </div>

                                    @if ($errors->has('method'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('method') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    {{ Form::button(trans('cortex/fort::common.reset'), ['class' => 'btn btn-default', 'type' => 'reset']) }}
                                    {{ Form::button('<i class="fa fa-phone"></i> '.trans('cortex/fort::common.verification_phone_request'), ['class' => 'btn btn-primary', 'type' => 'submit']) }}
                                </div>
                            </div>

                        {{ Form::close() }}
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
