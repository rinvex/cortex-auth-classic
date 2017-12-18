<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Factories\MenuFactory;

Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->findBy('title', trans('cortex/foundation::common.access'), function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.abilities.index'], trans('cortex/fort::common.abilities'), 10, 'fa fa-sliders')->can('list-abilities');
        $dropdown->route(['adminarea.roles.index'], trans('cortex/fort::common.roles'), 20, 'fa fa-users')->can('list-roles');
    });

    $menu->findBy('title', trans('cortex/foundation::common.user'), function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.users.index'], trans('cortex/fort::common.users'), 10, 'fa fa-user')->can('list-users');
    });
});

Menu::modify('managerarea.sidebar', function (MenuFactory $menu) {
    $menu->findBy('title', trans('cortex/foundation::common.access'), function (MenuItem $dropdown) {
        $dropdown->route(['managerarea.roles.index'], trans('cortex/fort::common.roles'), 10, 'fa fa-users')->can('list-roles');
    });
});

Menu::modify('managerarea.sidebar', function (MenuFactory $menu) {
    $menu->findBy('title', trans('cortex/foundation::common.user'), function (MenuItem $dropdown) {
        $dropdown->route(['managerarea.users.index'], trans('cortex/fort::common.users'), 20, 'fa fa-user')->can('list-users');
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

    Menu::modify('frontarea.topbar', $userMenu);
    Menu::modify('adminarea.topbar', $userMenu);
    Menu::modify('tenantarea.topbar', $userMenu);
    Menu::modify('managerarea.topbar', $userMenu);
} else {
    Menu::modify('frontarea.topbar', function (MenuFactory $menu) {
        $menu->route(['frontarea.login'], trans('cortex/fort::common.login'), 10);
        $menu->route(['frontarea.register'], trans('cortex/fort::common.register'), 20);
    });

    Menu::modify('tenantarea.topbar', function (MenuFactory $menu) {
        $menu->route(['tenantarea.login'], trans('cortex/fort::common.login'), 10);
        $menu->route(['tenantarea.register'], trans('cortex/fort::common.register'), 20);
    });
}
