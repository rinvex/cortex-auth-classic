<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

if ($user = request()->user()) {
    Menu::register('tenantarea.header.user', function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['tenantarea.cortex.auth.account'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
            $dropdown->divider();
            $dropdown->route(['tenantarea.cortex.auth.account.logout'], trans('cortex/auth::common.logout').Form::open(['url' => route('tenantarea.cortex.auth.account.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), null, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    });
} else {
    Menu::register('tenantarea.header.user', function (MenuGenerator $menu) {
        $menu->route(['tenantarea.cortex.auth.account.login'], trans('cortex/auth::common.login'));
        $menu->route(['tenantarea.cortex.auth.account.register'], trans('cortex/auth::common.register'));
    });
}

Menu::register('tenantarea.cortex.auth.account.sidebar', function (MenuGenerator $menu) {
    $menu->route(['tenantarea.cortex.auth.account'], trans('cortex/auth::common.account'), null, 'fa fa-user');
    $menu->route(['tenantarea.cortex.auth.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
    //$menu->route(['tenantarea.cortex.auth.account.attributes'], trans('cortex/auth::common.attributes'), null, 'fa fa-leaf');
    $menu->route(['tenantarea.cortex.auth.account.sessions'], trans('cortex/auth::common.sessions'), null, 'fa fa-list-alt');
    $menu->route(['tenantarea.cortex.auth.account.password'], trans('cortex/auth::common.password'), null, 'fa fa-key');
    $menu->route(['tenantarea.cortex.auth.account.twofactor'], trans('cortex/auth::common.twofactor'), null, 'fa fa-lock');
});
