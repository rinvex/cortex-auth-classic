<?php

declare(strict_types=1);

use Cortex\Auth\Models\Role;
use Cortex\Auth\Models\Admin;
use Cortex\Auth\Models\Member;
use Cortex\Auth\Models\Ability;
use Cortex\Auth\Models\Manager;
use Cortex\Auth\Models\Sentinel;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

// Adminarea breadcrumbs
Breadcrumbs::register('adminarea.roles.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.roles'), route('adminarea.roles.index'));
});

Breadcrumbs::register('adminarea.roles.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_role'), route('adminarea.roles.create'));
});

Breadcrumbs::register('adminarea.roles.edit', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push($role->name, route('adminarea.roles.edit', ['role' => $role]));
});

Breadcrumbs::register('adminarea.roles.logs', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push($role->name, route('adminarea.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.roles.logs', ['role' => $role]));
});

Breadcrumbs::register('adminarea.admins.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.admins'), route('adminarea.admins.index'));
});

Breadcrumbs::register('adminarea.admins.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.admins.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_admin'), route('adminarea.admins.create'));
});

Breadcrumbs::register('adminarea.admins.edit', function (BreadcrumbsGenerator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.admins.index');
    $breadcrumbs->push($admin->username, route('adminarea.admins.edit', ['admin' => $admin]));
});

Breadcrumbs::register('adminarea.admins.logs', function (BreadcrumbsGenerator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.admins.index');
    $breadcrumbs->push($admin->username, route('adminarea.admins.edit', ['admin' => $admin]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.admins.logs', ['admin' => $admin]));
});

Breadcrumbs::register('adminarea.admins.activities', function (BreadcrumbsGenerator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.admins.index');
    $breadcrumbs->push($admin->username, route('adminarea.admins.edit', ['admin' => $admin]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.admins.activities', ['admin' => $admin]));
});

Breadcrumbs::register('adminarea.admins.attributes', function (BreadcrumbsGenerator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.admins.index');
    $breadcrumbs->push($admin->username, route('adminarea.admins.edit', ['admin' => $admin]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('adminarea.admins.attributes', ['admin' => $admin]));
});

Breadcrumbs::register('adminarea.members.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.members'), route('adminarea.members.index'));
});

Breadcrumbs::register('adminarea.members.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_member'), route('adminarea.members.create'));
});

Breadcrumbs::register('adminarea.members.edit', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.members.index');
    $breadcrumbs->push($member->username, route('adminarea.members.edit', ['member' => $member]));
});

Breadcrumbs::register('adminarea.members.logs', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.members.index');
    $breadcrumbs->push($member->username, route('adminarea.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.members.logs', ['member' => $member]));
});

Breadcrumbs::register('adminarea.members.activities', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.members.index');
    $breadcrumbs->push($member->username, route('adminarea.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.members.activities', ['member' => $member]));
});

Breadcrumbs::register('adminarea.members.attributes', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.members.index');
    $breadcrumbs->push($member->username, route('adminarea.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('adminarea.members.attributes', ['member' => $member]));
});

Breadcrumbs::register('adminarea.managers.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.managers'), route('adminarea.managers.index'));
});

Breadcrumbs::register('adminarea.managers.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_manager'), route('adminarea.managers.create'));
});

Breadcrumbs::register('adminarea.managers.edit', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.managers.index');
    $breadcrumbs->push($manager->username, route('adminarea.managers.edit', ['manager' => $manager]));
});

Breadcrumbs::register('adminarea.managers.logs', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.managers.index');
    $breadcrumbs->push($manager->username, route('adminarea.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.managers.logs', ['manager' => $manager]));
});

Breadcrumbs::register('adminarea.managers.activities', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.managers.index');
    $breadcrumbs->push($manager->username, route('adminarea.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.managers.activities', ['manager' => $manager]));
});

Breadcrumbs::register('adminarea.managers.attributes', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.managers.index');
    $breadcrumbs->push($manager->username, route('adminarea.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('adminarea.managers.attributes', ['manager' => $manager]));
});

Breadcrumbs::register('adminarea.sentinels.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.sentinels'), route('adminarea.sentinels.index'));
});

Breadcrumbs::register('adminarea.sentinels.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.sentinels.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_sentinel'), route('adminarea.sentinels.create'));
});

Breadcrumbs::register('adminarea.sentinels.edit', function (BreadcrumbsGenerator $breadcrumbs, Sentinel $sentinel) {
    $breadcrumbs->parent('adminarea.sentinels.index');
    $breadcrumbs->push($sentinel->username, route('adminarea.sentinels.edit', ['sentinel' => $sentinel]));
});

Breadcrumbs::register('adminarea.sentinels.logs', function (BreadcrumbsGenerator $breadcrumbs, Sentinel $sentinel) {
    $breadcrumbs->parent('adminarea.sentinels.index');
    $breadcrumbs->push($sentinel->username, route('adminarea.sentinels.edit', ['sentinel' => $sentinel]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.sentinels.logs', ['sentinel' => $sentinel]));
});

Breadcrumbs::register('adminarea.abilities.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.adminarea'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.abilities'), route('adminarea.abilities.index'));
});

Breadcrumbs::register('adminarea.abilities.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_ability'), route('adminarea.abilities.create'));
});

Breadcrumbs::register('adminarea.abilities.edit', function (BreadcrumbsGenerator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push($ability->name, route('adminarea.abilities.edit', ['ability' => $ability]));
});

Breadcrumbs::register('adminarea.abilities.logs', function (BreadcrumbsGenerator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push($ability->name, route('adminarea.abilities.edit', ['ability' => $ability]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.abilities.logs', ['ability' => $ability]));
});
