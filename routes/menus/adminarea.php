<?php

declare(strict_types=1);

use Cortex\Auth\Models\Role;
use Cortex\Auth\Models\Admin;
use Cortex\Auth\Models\Member;
use Cortex\Auth\Models\Ability;
use Cortex\Auth\Models\Manager;
use Cortex\Auth\Models\Guardian;
use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

Menu::register('adminarea.sidebar', function (MenuGenerator $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.access'), null, 'fa fa-user-circle-o', 'header', [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.cortex.auth.abilities.index'], trans('cortex/auth::common.abilities'), null, 'fa fa-sliders')->ifCan('list', app('cortex.auth.ability'))->activateOnRoute('adminarea.cortex.auth.abilities');
        $dropdown->route(['adminarea.cortex.auth.roles.index'], trans('cortex/auth::common.roles'), null, 'fa fa-users')->ifCan('list', app('cortex.auth.role'))->activateOnRoute('adminarea.cortex.auth.roles');
    });

    $menu->findByTitleOrAdd(trans('cortex/foundation::common.user'), null, 'fa fa-users', 'header', [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.cortex.auth.admins.index'], trans('cortex/auth::common.admins'), null, 'fa fa-user')->ifCan('list', app('cortex.auth.admin'))->activateOnRoute('adminarea.cortex.auth.admins');
        $dropdown->route(['adminarea.cortex.auth.managers.index'], trans('cortex/auth::common.managers'), null, 'fa fa-user')->ifCan('list', app('cortex.auth.manager'))->activateOnRoute('adminarea.cortex.auth.managers');
        $dropdown->route(['adminarea.cortex.auth.members.index'], trans('cortex/auth::common.members'), null, 'fa fa-user')->ifCan('list', app('cortex.auth.member'))->activateOnRoute('adminarea.cortex.auth.members');
        $dropdown->route(['adminarea.cortex.auth.guardians.index'], trans('cortex/auth::common.guardians'), null, 'fa fa-user')->ifCan('list', app('cortex.auth.guardian'))->activateOnRoute('adminarea.cortex.auth.guardians');
    });
});

if ($user = auth()->guard(app('request.guard'))->user()) {
    Menu::register('adminarea.header.user', function (MenuGenerator $menu) use ($user) {
        $menu->dropdown(function (MenuItem $dropdown) {
            $dropdown->route(['adminarea.cortex.auth.account'], trans('cortex/auth::common.account'), null, 'fa fa-user');
            $dropdown->route(['adminarea.cortex.auth.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
            $dropdown->divider();
            $dropdown->route(['adminarea.cortex.auth.account.login'], trans('cortex/auth::common.logout').Form::open(['url' => route('adminarea.cortex.auth.account.login'), 'id' => 'logout-form', 'style' => 'display: none;']).Form::close(), null, 'fa fa-sign-out', ['onclick' => "event.preventDefault(); document.getElementById('logout-form').submit();"]);
        }, $user->username, 10, 'fa fa-user');
    });

    Menu::register('adminarea.cortex.auth.account.sidebar', function (MenuGenerator $menu) {
        $menu->route(['adminarea.cortex.auth.account.settings'], trans('cortex/auth::common.settings'), null, 'fa fa-cogs');
        //$menu->route(['adminarea.cortex.auth.account.attributes'], trans('cortex/auth::common.attributes'), null, 'fa fa-leaf');
        $menu->route(['adminarea.cortex.auth.account.sessions'], trans('cortex/auth::common.sessions'), null, 'fa fa-list-alt');
        $menu->route(['adminarea.cortex.auth.account.password'], trans('cortex/auth::common.password'), null, 'fa fa-key');
        $menu->route(['adminarea.cortex.auth.account.twofactor'], trans('cortex/auth::common.twofactor'), null, 'fa fa-lock');
    });
} else {
    Menu::register('adminarea.header.user', function (MenuGenerator $menu) {
        $menu->route(['adminarea.cortex.auth.account.login'], trans('cortex/auth::common.login'));
    });
}

Menu::register('adminarea.cortex.auth.abilities.tabs', function (MenuGenerator $menu, Ability $ability) {
    $menu->route(['adminarea.cortex.auth.abilities.import'], trans('cortex/auth::common.records'))->ifCan('import', $ability)->if(Route::is('adminarea.cortex.auth.abilities.import*'));
    $menu->route(['adminarea.cortex.auth.abilities.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $ability)->if(Route::is('adminarea.cortex.auth.abilities.import*'));
    $menu->route(['adminarea.cortex.auth.abilities.create'], trans('cortex/auth::common.details'))->ifCan('create', $ability)->if(Route::is('adminarea.cortex.auth.abilities.create'));
    $menu->route(['adminarea.cortex.auth.abilities.edit', ['ability' => $ability]], trans('cortex/auth::common.details'))->ifCan('update', $ability)->if($ability->exists);
    $menu->route(['adminarea.cortex.auth.abilities.logs', ['ability' => $ability]], trans('cortex/auth::common.logs'))->ifCan('audit', $ability)->if($ability->exists);
});

Menu::register('adminarea.cortex.auth.roles.tabs', function (MenuGenerator $menu, Role $role) {
    $menu->route(['adminarea.cortex.auth.roles.import'], trans('cortex/auth::common.records'))->ifCan('import', $role)->if(Route::is('adminarea.cortex.auth.roles.import*'));
    $menu->route(['adminarea.cortex.auth.roles.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $role)->if(Route::is('adminarea.cortex.auth.roles.import*'));
    $menu->route(['adminarea.cortex.auth.roles.create'], trans('cortex/auth::common.details'))->ifCan('create', $role)->if(Route::is('adminarea.cortex.auth.roles.create'));
    $menu->route(['adminarea.cortex.auth.roles.edit', ['role' => $role]], trans('cortex/auth::common.details'))->ifCan('update', $role)->if($role->exists);
    $menu->route(['adminarea.cortex.auth.roles.logs', ['role' => $role]], trans('cortex/auth::common.logs'))->ifCan('audit', $role)->if($role->exists);
});

Menu::register('adminarea.cortex.auth.admins.tabs', function (MenuGenerator $menu, Admin $admin) {
    $menu->route(['adminarea.cortex.auth.admins.import'], trans('cortex/auth::common.records'))->ifCan('import', $admin)->if(Route::is('adminarea.cortex.auth.admins.import*'));
    $menu->route(['adminarea.cortex.auth.admins.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $admin)->if(Route::is('adminarea.cortex.auth.admins.import*'));
    $menu->route(['adminarea.cortex.auth.admins.create'], trans('cortex/auth::common.details'))->ifCan('create', $admin)->if(Route::is('adminarea.cortex.auth.admins.create'));
    $menu->route(['adminarea.cortex.auth.admins.edit', ['admin' => $admin]], trans('cortex/auth::common.details'))->ifCan('update', $admin)->if($admin->exists);
    //$menu->route(['adminarea.cortex.auth.admins.attributes', ['admin' => $admin]], trans('cortex/auth::common.attributes'))->ifCan('update', $admin)->if($admin->exists);
    $menu->route(['adminarea.cortex.auth.admins.logs', ['admin' => $admin]], trans('cortex/auth::common.logs'))->ifCan('audit', $admin)->if($admin->exists);
    $menu->route(['adminarea.cortex.auth.admins.activities', ['admin' => $admin]], trans('cortex/auth::common.activities'))->ifCan('audit', $admin)->if($admin->exists);
});

Menu::register('adminarea.cortex.auth.managers.tabs', function (MenuGenerator $menu, Manager $manager) {
    $menu->route(['adminarea.cortex.auth.managers.import'], trans('cortex/auth::common.records'))->ifCan('import', $manager)->if(Route::is('adminarea.cortex.auth.managers.import*'));
    $menu->route(['adminarea.cortex.auth.managers.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $manager)->if(Route::is('adminarea.cortex.auth.managers.import*'));
    $menu->route(['adminarea.cortex.auth.managers.create'], trans('cortex/auth::common.details'))->ifCan('create', $manager)->if(Route::is('adminarea.cortex.auth.managers.create'));
    $menu->route(['adminarea.cortex.auth.managers.edit', ['manager' => $manager]], trans('cortex/auth::common.details'))->ifCan('update', $manager)->if($manager->exists);
    //$menu->route(['adminarea.cortex.auth.managers.attributes', ['manager' => $manager]], trans('cortex/auth::common.attributes'))->ifCan('update', $manager)->if($manager->exists);
    $menu->route(['adminarea.cortex.auth.managers.logs', ['manager' => $manager]], trans('cortex/auth::common.logs'))->ifCan('audit', $manager)->if($manager->exists);
    $menu->route(['adminarea.cortex.auth.managers.activities', ['manager' => $manager]], trans('cortex/auth::common.activities'))->ifCan('audit', $manager)->if($manager->exists);
});

Menu::register('adminarea.cortex.auth.members.tabs', function (MenuGenerator $menu, Member $member) {
    $menu->route(['adminarea.cortex.auth.members.import'], trans('cortex/auth::common.records'))->ifCan('import', $member)->if(Route::is('adminarea.cortex.auth.members.import*'));
    $menu->route(['adminarea.cortex.auth.members.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $member)->if(Route::is('adminarea.cortex.auth.members.import*'));
    $menu->route(['adminarea.cortex.auth.members.create'], trans('cortex/auth::common.details'))->ifCan('create', $member)->if(Route::is('adminarea.cortex.auth.members.create'));
    $menu->route(['adminarea.cortex.auth.members.edit', ['member' => $member]], trans('cortex/auth::common.details'))->ifCan('update', $member)->if($member->exists);
    //$menu->route(['adminarea.cortex.auth.members.attributes', ['member' => $member]], trans('cortex/auth::common.attributes'))->ifCan('update', $member)->if($member->exists);
    $menu->route(['adminarea.cortex.auth.members.logs', ['member' => $member]], trans('cortex/auth::common.logs'))->ifCan('audit', $member)->if($member->exists);
    $menu->route(['adminarea.cortex.auth.members.activities', ['member' => $member]], trans('cortex/auth::common.activities'))->ifCan('audit', $member)->if($member->exists);
});

Menu::register('adminarea.cortex.auth.guardians.tabs', function (MenuGenerator $menu, Guardian $guardian) {
    $menu->route(['adminarea.cortex.auth.guardians.import'], trans('cortex/auth::common.records'))->ifCan('import', $guardian)->if(Route::is('adminarea.cortex.auth.guardians.import*'));
    $menu->route(['adminarea.cortex.auth.guardians.import.logs'], trans('cortex/auth::common.logs'))->ifCan('import', $guardian)->if(Route::is('adminarea.cortex.auth.guardians.import*'));
    $menu->route(['adminarea.cortex.auth.guardians.create'], trans('cortex/auth::common.details'))->ifCan('create', $guardian)->if(Route::is('adminarea.cortex.auth.guardians.create'));
    $menu->route(['adminarea.cortex.auth.guardians.edit', ['guardian' => $guardian]], trans('cortex/auth::common.details'))->ifCan('update', $guardian)->if($guardian->exists);
    $menu->route(['adminarea.cortex.auth.guardians.logs', ['guardian' => $guardian]], trans('cortex/auth::common.logs'))->ifCan('audit', $guardian)->if($guardian->exists);
});
