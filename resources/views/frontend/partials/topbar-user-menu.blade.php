@if (Auth::user())
    <!-- User Account: style can be found in dropdown.less -->
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            {{ Auth::user()->username }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="{{ route('frontend.account.settings') }}"><i class="fa fa-user"></i> {{ trans('cortex/fort::common.settings') }}</a></li>
            <li><a href="{{ route('frontend.account.sessions') }}"><i class="fa fa-id-badge"></i> {{ trans('cortex/fort::common.sessions') }}</a></li>
            <li role="separator" class="divider"></li>
            <li>
                <a href="{{ route('frontend.auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> {{ trans('cortex/fort::common.logout') }}</a>
                {{ Form::open(['url' => route('frontend.auth.logout'), 'id' => 'logout-form', 'style' => 'display: none;']) }}
                {{ Form::close() }}
            </li>
        </ul>
    </li>
@else
    <li><a href="{{ route('frontend.auth.login') }}">{{ trans('cortex/fort::common.login') }}</a></li>
    <li><a href="{{ route('frontend.auth.register') }}">{{ trans('cortex/fort::common.register') }}</a></li>
@endif
