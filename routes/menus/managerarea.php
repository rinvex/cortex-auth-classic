<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

if ($user = request()->user()) {
    Menu::register('managerarea.sidebar', function (MenuGenerator $menu) {
        $menu->findByTitleOrAdd(trans('cortex/auth::common.managers'), 40, 'fa fa-file-text-o', 'header', [], [], function (MenuItem $dropdown) {
            $dropdown->route(['managerarea.cortex.auth.managers.index'], trans('cortex/auth::common.managers'), 20, 'fa fa-files-o')->ifCan('list', app('cortex.auth.manager'))->activateOnRoute('managerarea.cortex.auth.managers');
        });
    });
    
    Menu::register('managerarea.header.user', function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['managerarea.cortex.auth.account'], trans('cortex/auth::common.account'), null, 'fa fa-cogs');
            $dropdown->route(['managerarea.cortex.auth.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
            $dropdown->divider();
            $dropdown->route(['managerarea.cortex.auth.account.logout'], trans('cortex/auth::common.logout').Form::open(['url' => route('managerarea.cortex.auth.account.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), null, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    });

    Menu::register('managerarea.cortex.auth.account.sidebar', function (MenuGenerator $menu) {
        $menu->route(['managerarea.cortex.auth.account'], trans('cortex/auth::common.account'), null, 'fa fa-user');
        $menu->route(['managerarea.cortex.auth.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
        //$menu->route(['managerarea.cortex.auth.account.attributes'], trans('cortex/auth::common.attributes'), null, 'fa fa-leaf');
        $menu->route(['managerarea.cortex.auth.account.sessions'], trans('cortex/auth::common.sessions'), null, 'fa fa-list-alt');
        $menu->route(['managerarea.cortex.auth.account.password'], trans('cortex/auth::common.password'), null, 'fa fa-key');
        $menu->route(['managerarea.cortex.auth.account.twofactor'], trans('cortex/auth::common.twofactor'), null, 'fa fa-lock');
    });
} else {
    Menu::register('managerarea.header.user', function (MenuGenerator $menu) {
        $menu->route(['managerarea.cortex.auth.account.login'], trans('cortex/auth::common.login'));
    });
}
