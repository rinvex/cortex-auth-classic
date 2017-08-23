<?php

declare(strict_types=1);

use Rinvex\Fort\Contracts\RoleContract;
use Rinvex\Fort\Contracts\UserContract;
use Rinvex\Fort\Contracts\AbilityContract;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('backend.roles.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.backend'), route('backend.home'));
    $breadcrumbs->push(trans('cortex/fort::common.roles'), route('backend.roles.index'));
});

Breadcrumbs::register('backend.roles.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('backend.roles.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_role'), route('backend.roles.create'));
});

Breadcrumbs::register('backend.roles.edit', function (BreadcrumbsGenerator $breadcrumbs, RoleContract $role) {
    $breadcrumbs->parent('backend.roles.index');
    $breadcrumbs->push($role->name, route('backend.roles.edit', ['role' => $role]));
});

Breadcrumbs::register('backend.roles.logs', function (BreadcrumbsGenerator $breadcrumbs, RoleContract $role) {
    $breadcrumbs->parent('backend.roles.index');
    $breadcrumbs->push($role->name, route('backend.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('backend.roles.logs', ['role' => $role]));
});

Breadcrumbs::register('backend.users.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.backend'), route('backend.home'));
    $breadcrumbs->push(trans('cortex/fort::common.users'), route('backend.users.index'));
});

Breadcrumbs::register('backend.users.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('backend.users.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_user'), route('backend.users.create'));
});

Breadcrumbs::register('backend.users.edit', function (BreadcrumbsGenerator $breadcrumbs, UserContract $user) {
    $breadcrumbs->parent('backend.users.index');
    $breadcrumbs->push($user->username, route('backend.users.edit', ['user' => $user]));
});

Breadcrumbs::register('backend.users.logs', function (BreadcrumbsGenerator $breadcrumbs, UserContract $user) {
    $breadcrumbs->parent('backend.users.index');
    $breadcrumbs->push($user->username, route('backend.users.edit', ['user' => $user]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('backend.users.logs', ['user' => $user]));
});

Breadcrumbs::register('backend.users.activities', function (BreadcrumbsGenerator $breadcrumbs, UserContract $user) {
    $breadcrumbs->parent('backend.users.index');
    $breadcrumbs->push($user->username, route('backend.users.edit', ['user' => $user]));
    $breadcrumbs->push(trans('cortex/fort::common.activities'), route('backend.users.activities', ['user' => $user]));
});

Breadcrumbs::register('backend.abilities.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.backend'), route('backend.home'));
    $breadcrumbs->push(trans('cortex/fort::common.abilities'), route('backend.abilities.index'));
});

Breadcrumbs::register('backend.abilities.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('backend.abilities.index');
    $breadcrumbs->push(trans('cortex/fort::common.create_ability'), route('backend.abilities.create'));
});

Breadcrumbs::register('backend.abilities.edit', function (BreadcrumbsGenerator $breadcrumbs, AbilityContract $ability) {
    $breadcrumbs->parent('backend.abilities.index');
    $breadcrumbs->push($ability->name, route('backend.abilities.edit', ['ability' => $ability]));
});

Breadcrumbs::register('backend.abilities.logs', function (BreadcrumbsGenerator $breadcrumbs, AbilityContract $ability) {
    $breadcrumbs->parent('backend.abilities.index');
    $breadcrumbs->push($ability->name, route('backend.abilities.edit', ['ability' => $ability]));
    $breadcrumbs->push(trans('cortex/fort::common.logs'), route('backend.abilities.logs', ['ability' => $ability]));
});
