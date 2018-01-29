<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

Menu::register('adminarea.sidebar', function (MenuGenerator $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.access'), 20, 'fa fa-user-circle-o', [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.abilities.index'], trans('cortex/fort::common.abilities'), 10, 'fa fa-sliders')->ifCan('list-abilities')->activateOnRoute('adminarea.abilities');
        $dropdown->route(['adminarea.roles.index'], trans('cortex/fort::common.roles'), 20, 'fa fa-users')->ifCan('list-roles')->activateOnRoute('adminarea.roles');
    });

    $menu->findByTitleOrAdd(trans('cortex/foundation::common.user'), 30, 'fa fa-users', [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.users.index'], trans('cortex/fort::common.users'), 10, 'fa fa-user')->ifCan('list-users')->activateOnRoute('adminarea.users');
    });
});

Menu::register('managerarea.sidebar', function (MenuGenerator $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.access'), 20, 'fa fa-user-circle-o', [], function (MenuItem $dropdown) {
        $dropdown->route(['managerarea.roles.index'], trans('cortex/fort::common.roles'), 10, 'fa fa-users')->ifCan('list-roles')->activateOnRoute('managerarea.roles');
    });

    $menu->findByTitleOrAdd(trans('cortex/foundation::common.user'), 30, 'fa fa-users', [], function (MenuItem $dropdown) {
        $dropdown->route(['managerarea.users.index'], trans('cortex/fort::common.users'), 20, 'fa fa-user')->ifCan('list-users')->activateOnRoute('managerarea.users');
    });
});

if ($user = auth()->user()) {
    $userMenu = function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['adminarea.home'], trans('cortex/foundation::common.adminarea'), 10, 'fa fa-dashboard')->ifCan('access-adminarea');
            $dropdown->route(['managerarea.home'], trans('cortex/tenants::common.managerarea'), 20, 'fa fa-briefcase')->ifCan('access-managerarea');
            $dropdown->route(['frontarea.account'], trans('cortex/fort::common.settings'), 30, 'fa fa-cogs');
            $dropdown->divider(40);
            $dropdown->route(['frontarea.logout'], trans('cortex/fort::common.logout').Form::open(['url' => route('frontarea.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), 50, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    };

    $tenantUserMenu = function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['adminarea.home'], trans('cortex/foundation::common.adminarea'), 10, 'fa fa-dashboard')->ifCan('access-adminarea');
            $dropdown->route(['managerarea.home'], trans('cortex/tenants::common.managerarea'), 20, 'fa fa-briefcase')->ifCan('access-managerarea');
            $dropdown->route(['tenantarea.account'], trans('cortex/fort::common.settings'), 30, 'fa fa-cogs');
            $dropdown->divider(40);
            $dropdown->route(['tenantarea.logout'], trans('cortex/fort::common.logout').Form::open(['url' => route('tenantarea.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), 50, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    };

    $accountSidebarMenu = function (MenuGenerator $menu) {
        $menu->route(['frontarea.account.settings'], trans('cortex/fort::common.settings'), 10, 'fa fa-cogs');
        $menu->route(['frontarea.account.attributes'], trans('cortex/fort::common.attributes'), 20, 'fa fa-leaf');
        $menu->route(['frontarea.account.sessions'], trans('cortex/fort::common.sessions'), 30, 'fa fa-list-alt');
        $menu->route(['frontarea.account.password'], trans('cortex/fort::common.password'), 40, 'fa fa-key');
        $menu->route(['frontarea.account.twofactor.index'], trans('cortex/fort::common.twofactor'), 50, 'fa fa-lock');
    };

    $tenantAccountSidebarMenu = function (MenuGenerator $menu) {
        $menu->route(['tenantarea.account.settings'], trans('cortex/fort::common.settings'), 10, 'fa fa-cogs');
        $menu->route(['tenantarea.account.attributes'], trans('cortex/fort::common.attributes'), 20, 'fa fa-leaf');
        $menu->route(['tenantarea.account.sessions'], trans('cortex/fort::common.sessions'), 30, 'fa fa-list-alt');
        $menu->route(['tenantarea.account.password'], trans('cortex/fort::common.password'), 40, 'fa fa-key');
        $menu->route(['tenantarea.account.twofactor.index'], trans('cortex/fort::common.twofactor'), 50, 'fa fa-lock');
    };

    Menu::register('frontarea.header', $userMenu);
    Menu::register('adminarea.header', $userMenu);
    Menu::register('tenantarea.header', $tenantUserMenu);
    Menu::register('managerarea.header', $tenantUserMenu);
    Menu::register('frontarea.account.sidebar', $accountSidebarMenu);
    Menu::register('tenantarea.account.sidebar', $tenantAccountSidebarMenu);
} else {
    Menu::register('frontarea.header', function (MenuGenerator $menu) {
        $menu->route(['frontarea.login'], trans('cortex/fort::common.login'), 10);
        $menu->route(['frontarea.register'], trans('cortex/fort::common.register'), 20);
    });

    Menu::register('tenantarea.header', function (MenuGenerator $menu) {
        $menu->route(['tenantarea.login'], trans('cortex/fort::common.login'), 10);
        $menu->route(['tenantarea.register'], trans('cortex/fort::common.register'), 20);
    });
}
