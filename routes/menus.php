<?php

declare(strict_types=1);

use Cortex\Fort\Models\Role;
use Cortex\Fort\Models\Admin;
use Cortex\Fort\Models\Member;
use Cortex\Fort\Models\Manager;
use Cortex\Fort\Models\Ability;
use Cortex\Fort\Models\Sentinel;
use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

Menu::register('adminarea.sidebar', function (MenuGenerator $menu, Ability $ability, Role $role, Admin $admin, Member $member, Manager $manager, Sentinel $sentinel) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.access'), 20, 'fa fa-user-circle-o', [], function (MenuItem $dropdown) use ($ability, $role) {
        $dropdown->route(['adminarea.abilities.index'], trans('cortex/fort::common.abilities'), 10, 'fa fa-sliders')->ifCan('list', $ability)->activateOnRoute('adminarea.abilities');
        $dropdown->route(['adminarea.roles.index'], trans('cortex/fort::common.roles'), 20, 'fa fa-users')->ifCan('list', $role)->activateOnRoute('adminarea.roles');
    });

    $menu->findByTitleOrAdd(trans('cortex/foundation::common.user'), 30, 'fa fa-users', [], function (MenuItem $dropdown) use ($admin, $member, $manager, $sentinel) {
        $dropdown->route(['adminarea.admins.index'], trans('cortex/fort::common.admins'), 10, 'fa fa-user')->ifCan('list', $admin)->activateOnRoute('adminarea.admins');
        $dropdown->route(['adminarea.members.index'], trans('cortex/fort::common.members'), 10, 'fa fa-user')->ifCan('list', $member)->activateOnRoute('adminarea.members');
        $dropdown->route(['adminarea.managers.index'], trans('cortex/fort::common.managers'), 10, 'fa fa-user')->ifCan('list', $manager)->activateOnRoute('adminarea.managers');
        $dropdown->route(['adminarea.sentinels.index'], trans('cortex/fort::common.sentinels'), 10, 'fa fa-user')->ifCan('list', $sentinel)->activateOnRoute('adminarea.sentinels');
    });
});

if ($user = auth()->guard(request('guard'))->user()) {
    $userHeaderMenu = function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route([request('accessarea').'.account'], trans('cortex/fort::common.settings'), null, 'fa fa-cogs');
            $dropdown->divider();
            $dropdown->route([request('accessarea').'.logout'], trans('cortex/fort::common.logout').Form::open(['url' => route(request('accessarea').'.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), null, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    };

    $userSidebarMenu = function (MenuGenerator $menu) {
        $menu->route([request('accessarea').'.account.settings'], trans('cortex/fort::common.settings'), null, 'fa fa-cogs');
        $menu->route([request('accessarea').'.account.attributes'], trans('cortex/fort::common.attributes'), null, 'fa fa-leaf');
        $menu->route([request('accessarea').'.account.sessions'], trans('cortex/fort::common.sessions'), null, 'fa fa-list-alt');
        $menu->route([request('accessarea').'.account.password'], trans('cortex/fort::common.password'), null, 'fa fa-key');
        $menu->route([request('accessarea').'.account.twofactor.index'], trans('cortex/fort::common.twofactor'), null, 'fa fa-lock');
    };

    Menu::register(request('accessarea').'.header', $userHeaderMenu);
    Menu::register(request('accessarea').'.account.sidebar', $userSidebarMenu);
} else {
    Menu::register(request('accessarea').'.header', function (MenuGenerator $menu) {
        $menu->route([request('accessarea').'.login'], trans('cortex/fort::common.login'));
        $menu->route([request('accessarea').'.register'], trans('cortex/fort::common.register'));
    });
}

Menu::register('adminarea.abilities.tabs', function (MenuGenerator $menu, Ability $ability) {
    $menu->route(['adminarea.abilities.create'], trans('cortex/fort::common.details'))->ifCan('create', $ability)->if(! $ability->exists);
    $menu->route(['adminarea.abilities.edit', ['ability' => $ability]], trans('cortex/fort::common.details'))->ifCan('update', $ability)->if($ability->exists);
    $menu->route(['adminarea.abilities.logs', ['ability' => $ability]], trans('cortex/fort::common.logs'))->ifCan('audit', $ability)->if($ability->exists);
});

Menu::register('adminarea.roles.tabs', function (MenuGenerator $menu, Role $role) {
    $menu->route(['adminarea.roles.create'], trans('cortex/fort::common.details'))->ifCan('create', $role)->if(! $role->exists);
    $menu->route(['adminarea.roles.edit', ['role' => $role]], trans('cortex/fort::common.details'))->ifCan('update', $role)->if($role->exists);
    $menu->route(['adminarea.roles.logs', ['role' => $role]], trans('cortex/fort::common.logs'))->ifCan('audit', $role)->if($role->exists);
});

Menu::register('adminarea.admins.tabs', function (MenuGenerator $menu, Admin $admin) {
    $menu->route(['adminarea.admins.create'], trans('cortex/fort::common.details'))->ifCan('create-admins')->if(! $admin->exists);
    $menu->route(['adminarea.admins.edit', ['admin' => $admin]], trans('cortex/fort::common.details'))->ifCan('update-admins')->if($admin->exists);
    $menu->route(['adminarea.admins.attributes', ['admin' => $admin]], trans('cortex/fort::common.attributes'))->ifCan('update-admins')->if($admin->exists);
    $menu->route(['adminarea.admins.logs', ['admin' => $admin]], trans('cortex/fort::common.logs'))->ifCan('audit-admins')->if($admin->exists);
    $menu->route(['adminarea.admins.activities', ['admin' => $admin]], trans('cortex/fort::common.activities'))->ifCan('audit-admins')->if($admin->exists);
});

Menu::register('adminarea.members.tabs', function (MenuGenerator $menu, Member $member) {
    $menu->route(['adminarea.members.create'], trans('cortex/fort::common.details'))->ifCan('create-members')->if(! $member->exists);
    $menu->route(['adminarea.members.edit', ['member' => $member]], trans('cortex/fort::common.details'))->ifCan('update-members')->if($member->exists);
    $menu->route(['adminarea.members.attributes', ['member' => $member]], trans('cortex/fort::common.attributes'))->ifCan('update-members')->if($member->exists);
    $menu->route(['adminarea.members.logs', ['member' => $member]], trans('cortex/fort::common.logs'))->ifCan('audit-members')->if($member->exists);
    $menu->route(['adminarea.members.activities', ['member' => $member]], trans('cortex/fort::common.activities'))->ifCan('audit-members')->if($member->exists);
});

Menu::register('adminarea.managers.tabs', function (MenuGenerator $menu, Manager $manager) {
    $menu->route(['adminarea.managers.create'], trans('cortex/fort::common.details'))->ifCan('create-managers')->if(! $manager->exists);
    $menu->route(['adminarea.managers.edit', ['manager' => $manager]], trans('cortex/fort::common.details'))->ifCan('update-managers')->if($manager->exists);
    $menu->route(['adminarea.managers.attributes', ['manager' => $manager]], trans('cortex/fort::common.attributes'))->ifCan('update-managers')->if($manager->exists);
    $menu->route(['adminarea.managers.logs', ['manager' => $manager]], trans('cortex/fort::common.logs'))->ifCan('audit-managers')->if($manager->exists);
    $menu->route(['adminarea.managers.activities', ['manager' => $manager]], trans('cortex/fort::common.activities'))->ifCan('audit-managers')->if($manager->exists);
});

Menu::register('adminarea.sentinels.tabs', function (MenuGenerator $menu, Sentinel $sentinel) {
    $menu->route(['adminarea.sentinels.create'], trans('cortex/fort::common.details'))->ifCan('create-sentinels')->if(! $sentinel->exists);
    $menu->route(['adminarea.sentinels.edit', ['sentinel' => $sentinel]], trans('cortex/fort::common.details'))->ifCan('update-sentinels')->if($sentinel->exists);
    $menu->route(['adminarea.sentinels.logs', ['sentinel' => $sentinel]], trans('cortex/fort::common.logs'))->ifCan('audit-sentinels')->if($sentinel->exists);
});
