<?php

declare(strict_types=1);

use Cortex\Auth\Models\Role;
use Cortex\Auth\Models\Admin;
use Cortex\Auth\Models\Ability;
use Cortex\Auth\Models\Guardian;
use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

Menu::register('adminarea.sidebar', function (MenuGenerator $menu, Ability $ability, Role $role, Admin $admin, Guardian $guardian) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.access'), null, 'fa fa-user-circle-o', [], function (MenuItem $dropdown) use ($ability, $role) {
        $dropdown->route(['adminarea.abilities.index'], trans('cortex/auth::common.abilities'), null, 'fa fa-sliders')->ifCan('list', $ability)->activateOnRoute('adminarea.abilities');
        $dropdown->route(['adminarea.roles.index'], trans('cortex/auth::common.roles'), null, 'fa fa-users')->ifCan('list', $role)->activateOnRoute('adminarea.roles');
    });

    $menu->findByTitleOrAdd(trans('cortex/foundation::common.user'), null, 'fa fa-users', [], function (MenuItem $dropdown) use ($admin, $guardian) {
        $dropdown->route(['adminarea.admins.index'], trans('cortex/auth::common.admins'), null, 'fa fa-user')->ifCan('list', $admin)->activateOnRoute('adminarea.admins');
        $dropdown->route(['adminarea.guardians.index'], trans('cortex/auth::common.guardians'), null, 'fa fa-user')->ifCan('list', $guardian)->activateOnRoute('adminarea.guardians');
    });
});

if ($user = auth()->guard(request()->route('guard'))->user()) {
    Menu::register('adminarea.header.user', function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['adminarea.account'], trans('cortex/auth::common.account'), null, 'fa fa-user');
            $dropdown->route(['adminarea.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
            $dropdown->divider();
            $dropdown->route(['adminarea.logout'], trans('cortex/auth::common.logout').Form::open(['url' => route('adminarea.logout'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), null, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    });

    Menu::register('adminarea.account.sidebar', function (MenuGenerator $menu) {
        $menu->route(['adminarea.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
        //$menu->route(['adminarea.account.attributes'], trans('cortex/auth::common.attributes'), null, 'fa fa-leaf');
        $menu->route(['adminarea.account.sessions'], trans('cortex/auth::common.sessions'), null, 'fa fa-list-alt');
        $menu->route(['adminarea.account.password'], trans('cortex/auth::common.password'), null, 'fa fa-key');
        $menu->route(['adminarea.account.twofactor'], trans('cortex/auth::common.twofactor'), null, 'fa fa-lock');
    });
} else {
    Menu::register('adminarea.header.user', function (MenuGenerator $menu) {
        $menu->route(['adminarea.login'], trans('cortex/auth::common.login'));
        $menu->route(['adminarea.register'], trans('cortex/auth::common.register'));
    });
}

Menu::register('adminarea.abilities.tabs', function (MenuGenerator $menu, Ability $ability) {
    $menu->route(['adminarea.abilities.import'], trans('cortex/auth::common.records'))->ifCan('import', $ability)->if(Route::is('adminarea.abilities.import*'));
    $menu->route(['adminarea.abilities.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $ability)->if(Route::is('adminarea.abilities.import*'));
    $menu->route(['adminarea.abilities.create'], trans('cortex/auth::common.details'))->ifCan('create', $ability)->if(Route::is('adminarea.abilities.create'));
    $menu->route(['adminarea.abilities.edit', ['ability' => $ability]], trans('cortex/auth::common.details'))->ifCan('update', $ability)->if($ability->exists);
    $menu->route(['adminarea.abilities.logs', ['ability' => $ability]], trans('cortex/auth::common.logs'))->ifCan('audit', $ability)->if($ability->exists);
});

Menu::register('adminarea.roles.tabs', function (MenuGenerator $menu, Role $role) {
    $menu->route(['adminarea.roles.import'], trans('cortex/auth::common.records'))->ifCan('import', $role)->if(Route::is('adminarea.roles.import*'));
    $menu->route(['adminarea.roles.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $role)->if(Route::is('adminarea.roles.import*'));
    $menu->route(['adminarea.roles.create'], trans('cortex/auth::common.details'))->ifCan('create', $role)->if(Route::is('adminarea.roles.create'));
    $menu->route(['adminarea.roles.edit', ['role' => $role]], trans('cortex/auth::common.details'))->ifCan('update', $role)->if($role->exists);
    $menu->route(['adminarea.roles.logs', ['role' => $role]], trans('cortex/auth::common.logs'))->ifCan('audit', $role)->if($role->exists);
});

Menu::register('adminarea.admins.tabs', function (MenuGenerator $menu, Admin $admin) {
    $menu->route(['adminarea.admins.import'], trans('cortex/auth::common.records'))->ifCan('import', $admin)->if(Route::is('adminarea.admins.import*'));
    $menu->route(['adminarea.admins.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $admin)->if(Route::is('adminarea.admins.import*'));
    $menu->route(['adminarea.admins.create'], trans('cortex/auth::common.details'))->ifCan('create', $admin)->if(Route::is('adminarea.admins.create'));
    $menu->route(['adminarea.admins.edit', ['admin' => $admin]], trans('cortex/auth::common.details'))->ifCan('update', $admin)->if($admin->exists);
    //$menu->route(['adminarea.admins.attributes', ['admin' => $admin]], trans('cortex/auth::common.attributes'))->ifCan('update', $admin)->if($admin->exists);
    $menu->route(['adminarea.admins.logs', ['admin' => $admin]], trans('cortex/auth::common.logs'))->ifCan('audit', $admin)->if($admin->exists);
    $menu->route(['adminarea.admins.activities', ['admin' => $admin]], trans('cortex/auth::common.activities'))->ifCan('audit', $admin)->if($admin->exists);
});

Menu::register('adminarea.guardians.tabs', function (MenuGenerator $menu, Guardian $guardian) {
    $menu->route(['adminarea.guardians.import'], trans('cortex/auth::common.records'))->ifCan('import', $guardian)->if(Route::is('adminarea.guardians.import*'));
    $menu->route(['adminarea.guardians.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $guardian)->if(Route::is('adminarea.guardians.import*'));
    $menu->route(['adminarea.guardians.create'], trans('cortex/auth::common.details'))->ifCan('create', $guardian)->if(Route::is('adminarea.guardians.create'));
    $menu->route(['adminarea.guardians.edit', ['guardian' => $guardian]], trans('cortex/auth::common.details'))->ifCan('update', $guardian)->if($guardian->exists);
    $menu->route(['adminarea.guardians.logs', ['guardian' => $guardian]], trans('cortex/auth::common.logs'))->ifCan('audit', $guardian)->if($guardian->exists);
});
