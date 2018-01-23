<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Factories\MenuFactory;

Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.access'), 20, 'fa fa-user-circle-o', [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.abilities.index'], trans('cortex/fort::common.abilities'), 10, 'fa fa-sliders')->ifCan('list-abilities')->activateOnRoute('adminarea.abilities');
        $dropdown->route(['adminarea.roles.index'], trans('cortex/fort::common.roles'), 20, 'fa fa-users')->ifCan('list-roles')->activateOnRoute('adminarea.roles');
    });

    $menu->findByTitleOrAdd(trans('cortex/foundation::common.user'), 30, 'fa fa-users', [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.users.index'], trans('cortex/fort::common.users'), 10, 'fa fa-user')->ifCan('list-users')->activateOnRoute('adminarea.users');
    });
});

Menu::modify('managerarea.sidebar', function (MenuFactory $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.access'), 20, 'fa fa-user-circle-o', [], function (MenuItem $dropdown) {
        $dropdown->route(['managerarea.roles.index'], trans('cortex/fort::common.roles'), 10, 'fa fa-users')->ifCan('list-roles')->activateOnRoute('managerarea.roles');
    });

    $menu->findByTitleOrAdd(trans('cortex/foundation::common.user'), 30, 'fa fa-users', [], function (MenuItem $dropdown) {
        $dropdown->route(['managerarea.users.index'], trans('cortex/fort::common.users'), 20, 'fa fa-user')->ifCan('list-users')->activateOnRoute('managerarea.users');
    });
});

if ($user = auth()->user()) {

    $userMenu = function (MenuFactory $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['frontarea.account.settings'], trans('cortex/fort::common.settings'), 10, 'fa fa-user');
            $dropdown->route(['frontarea.account.sessions'], trans('cortex/fort::common.sessions'), 20, 'fa fa-id-badge');
            $dropdown->divider(30);
            $dropdown->route(['frontarea.logout'], trans('cortex/fort::common.logout').Form::open(['url' => route('frontarea.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), 40, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    };

    $userSidebarMenu = function (MenuFactory $menu) use () {
        $menu->route(['frontarea.account.settings'], trans('cortex/fort::common.settings'), 10, 'fa fa-cogs');
        $menu->route(['frontarea.account.sessions'], trans('cortex/fort::common.sessions'), 20, 'fa fa-id-badge');
        $menu->route(['frontarea.account.twofactor.index'], trans('cortex/fort::common.twofactor'), 30, 'fa fa-lock')->hideWhen(function() {
           return empty(config('rinvex.fort.twofactor.providers'));
        });
    };

    Menu::modify('frontarea.header', $userMenu);
    Menu::modify('frontarea.user.sidebar', $userSidebarMenu);
    Menu::modify('adminarea.header', $userMenu);
    Menu::modify('tenantarea.header', $userMenu);
    Menu::modify('managerarea.header', $userMenu);

} else {

    Menu::modify('frontarea.header', function (MenuFactory $menu) {
        $menu->route(['frontarea.login'], trans('cortex/fort::common.login'), 10);
        $menu->route(['frontarea.register'], trans('cortex/fort::common.register'), 20);
    });

    Menu::modify('tenantarea.header', function (MenuFactory $menu) {
        $menu->route(['tenantarea.login'], trans('cortex/fort::common.login'), 10);
        $menu->route(['tenantarea.register'], trans('cortex/fort::common.register'), 20);
    });
}
