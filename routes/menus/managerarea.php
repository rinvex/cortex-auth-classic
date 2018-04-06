<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

if ($user = auth()->guard(request()->route('guard'))->user()) {
    Menu::register('managerarea.header.user', function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['managerarea.account'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
            $dropdown->divider();
            $dropdown->route(['managerarea.logout'], trans('cortex/auth::common.logout').Form::open(['url' => route('managerarea.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), null, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    });

    Menu::register('managerarea.account.sidebar', function (MenuGenerator $menu) {
        $menu->route(['managerarea.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
        //$menu->route(['managerarea.account.attributes'], trans('cortex/auth::common.attributes'), null, 'fa fa-leaf');
        $menu->route(['managerarea.account.sessions'], trans('cortex/auth::common.sessions'), null, 'fa fa-list-alt');
        $menu->route(['managerarea.account.password'], trans('cortex/auth::common.password'), null, 'fa fa-key');
        $menu->route(['managerarea.account.twofactor.index'], trans('cortex/auth::common.twofactor'), null, 'fa fa-lock');
    });
} else {
    Menu::register('managerarea.header.user', function (MenuGenerator $menu) {
        $menu->route(['managerarea.login'], trans('cortex/auth::common.login'));
        $menu->route(['managerarea.register'], trans('cortex/auth::common.register'));
    });
}
