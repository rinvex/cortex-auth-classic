<?php

declare(strict_types=1);

use Rinvex\Fort\Models\Role;
use Rinvex\Fort\Models\User;
use Rinvex\Fort\Models\Ability;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

// Adminarea breadcrumbs
Breadcrumbs::register('adminarea.roles.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/fort::common.roles'), route('adminarea.roles.index'));
});

Breadcrumbs::register('adminarea.roles.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_role'), route('adminarea.roles.create'));
});

Breadcrumbs::register('adminarea.roles.edit', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push($role->name, route('adminarea.roles.edit', ['role' => $role]));
});

Breadcrumbs::register('adminarea.roles.logs', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push($role->name, route('adminarea.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('adminarea.roles.logs', ['role' => $role]));
});

Breadcrumbs::register('adminarea.users.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/fort::common.users'), route('adminarea.users.index'));
});

Breadcrumbs::register('adminarea.users.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.users.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_user'), route('adminarea.users.create'));
});

Breadcrumbs::register('adminarea.users.edit', function (BreadcrumbsGenerator $breadcrumbs, User $user) {
    $breadcrumbs->parent('adminarea.users.index');
    $breadcrumbs->push($user->username, route('adminarea.users.edit', ['user' => $user]));
});

Breadcrumbs::register('adminarea.users.logs', function (BreadcrumbsGenerator $breadcrumbs, User $user) {
    $breadcrumbs->parent('adminarea.users.index');
    $breadcrumbs->push($user->username, route('adminarea.users.edit', ['user' => $user]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('adminarea.users.logs', ['user' => $user]));
});

Breadcrumbs::register('adminarea.users.activities', function (BreadcrumbsGenerator $breadcrumbs, User $user) {
    $breadcrumbs->parent('adminarea.users.index');
    $breadcrumbs->push($user->username, route('adminarea.users.edit', ['user' => $user]));
    $breadcrumbs->push(trans('cortex/fort::common.activities'), route('adminarea.users.activities', ['user' => $user]));
});

Breadcrumbs::register('adminarea.abilities.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/fort::common.abilities'), route('adminarea.abilities.index'));
});

Breadcrumbs::register('adminarea.abilities.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_ability'), route('adminarea.abilities.create'));
});

Breadcrumbs::register('adminarea.abilities.edit', function (BreadcrumbsGenerator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push($ability->name, route('adminarea.abilities.edit', ['ability' => $ability]));
});

Breadcrumbs::register('adminarea.abilities.logs', function (BreadcrumbsGenerator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push($ability->name, route('adminarea.abilities.edit', ['ability' => $ability]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('adminarea.abilities.logs', ['ability' => $ability]));
});

// Managerarea breadcrumbs
Breadcrumbs::register('managerarea.roles.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/tenants::common.managerarea'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/fort::common.roles'), route('managerarea.roles.index'));
});

Breadcrumbs::register('managerarea.roles.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.roles.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_role'), route('managerarea.roles.create'));
});

Breadcrumbs::register('managerarea.roles.edit', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('managerarea.roles.index');
    $breadcrumbs->push($role->name, route('managerarea.roles.edit', ['role' => $role]));
});

Breadcrumbs::register('managerarea.roles.logs', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('managerarea.roles.index');
    $breadcrumbs->push($role->name, route('managerarea.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('managerarea.roles.logs', ['role' => $role]));
});

Breadcrumbs::register('managerarea.users.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/tenants::common.managerarea'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/fort::common.users'), route('managerarea.users.index'));
});

Breadcrumbs::register('managerarea.users.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.users.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_user'), route('managerarea.users.create'));
});

Breadcrumbs::register('managerarea.users.edit', function (BreadcrumbsGenerator $breadcrumbs, User $user) {
    $breadcrumbs->parent('managerarea.users.index');
    $breadcrumbs->push($user->username, route('managerarea.users.edit', ['user' => $user]));
});

Breadcrumbs::register('managerarea.users.logs', function (BreadcrumbsGenerator $breadcrumbs, User $user) {
    $breadcrumbs->parent('managerarea.users.index');
    $breadcrumbs->push($user->username, route('managerarea.users.edit', ['user' => $user]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('managerarea.users.logs', ['user' => $user]));
});

Breadcrumbs::register('managerarea.users.activities', function (BreadcrumbsGenerator $breadcrumbs, User $user) {
    $breadcrumbs->parent('managerarea.users.index');
    $breadcrumbs->push($user->username, route('managerarea.users.edit', ['user' => $user]));
    $breadcrumbs->push(trans('cortex/fort::common.activities'), route('managerarea.users.activities', ['user' => $user]));
});
