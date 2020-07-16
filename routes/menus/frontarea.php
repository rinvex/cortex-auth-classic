<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

if ($user = auth()->guard(app('request.guard'))->user()) {
    Menu::register('frontarea.header.user', function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['frontarea.account'], trans('cortex/auth::common.account'), null, 'fa fa-cogs');
            $dropdown->route(['frontarea.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
            $dropdown->divider();
            $dropdown->route(['frontarea.logout'], trans('cortex/auth::common.logout').Form::open(['url' => route('frontarea.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), null, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    });

    Menu::register('frontarea.account.sidebar', function (MenuGenerator $menu) {
        $menu->route(['frontarea.account'], trans('cortex/auth::common.account'), null, 'fa fa-user');
        $menu->route(['frontarea.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
        //$menu->route(['frontarea.account.attributes'], trans('cortex/auth::common.attributes'), null, 'fa fa-leaf');
        $menu->route(['frontarea.account.sessions'], trans('cortex/auth::common.sessions'), null, 'fa fa-list-alt');
        $menu->route(['frontarea.account.password'], trans('cortex/auth::common.password'), null, 'fa fa-key');
        $menu->route(['frontarea.account.twofactor'], trans('cortex/auth::common.twofactor'), null, 'fa fa-lock');
    });
} else {
    Menu::register('frontarea.header.user', function (MenuGenerator $menu) {
        $menu->route(['frontarea.login'], trans('cortex/auth::common.login'));
        $menu->route(['frontarea.register'], trans('cortex/auth::common.register'));
    });
}
