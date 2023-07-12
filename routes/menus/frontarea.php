<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

if ($user = request()->user()) {
    Menu::register('frontarea.header.user', function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['frontarea.cortex.auth.account'], trans('cortex/auth::common.account'), null, 'fa fa-cogs');
            $dropdown->route(['frontarea.cortex.auth.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
            $dropdown->divider();
            $dropdown->route(['frontarea.cortex.auth.account.logout'], trans('cortex/auth::common.logout').Form::open(['url' => route('frontarea.cortex.auth.account.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), null, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    });
} else {
    Menu::register('frontarea.header.user', function (MenuGenerator $menu) {
        $menu->route(['frontarea.cortex.auth.account.login'], trans('cortex/auth::common.login'));
        $menu->route(['frontarea.cortex.auth.account.register'], trans('cortex/auth::common.register'));
    });
}

Menu::register('frontarea.cortex.auth.account.sidebar', function (MenuGenerator $menu) {
    $menu->route(['frontarea.cortex.auth.account'], trans('cortex/auth::common.account'), null, 'fa fa-user');
    $menu->route(['frontarea.cortex.auth.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
    $menu->route(['frontarea.cortex.auth.account.sessions'], trans('cortex/auth::common.sessions'), null, 'fa fa-list-alt');
    $menu->route(['frontarea.cortex.auth.account.password'], trans('cortex/auth::common.password'), null, 'fa fa-key');
    $menu->route(['frontarea.cortex.auth.account.twofactor'], trans('cortex/auth::common.twofactor'), null, 'fa fa-lock');
});
