<?php

declare(strict_types=1);

use Cortex\Auth\Models\Role;
use Cortex\Auth\Models\Admin;
use Cortex\Auth\Models\Member;
use Cortex\Auth\Models\Ability;
use Cortex\Auth\Models\Manager;
use Cortex\Auth\Models\Guardian;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('adminarea.home', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
});

Breadcrumbs::register('adminarea.roles.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.roles'), route('adminarea.roles.index'));
});

Breadcrumbs::register('adminarea.roles.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.roles.import'));
});

Breadcrumbs::register('adminarea.roles.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.roles.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.roles.import.logs'));
});

Breadcrumbs::register('adminarea.roles.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_role'), route('adminarea.roles.create'));
});

Breadcrumbs::register('adminarea.roles.edit', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push($role->title, route('adminarea.roles.edit', ['role' => $role]));
});

Breadcrumbs::register('adminarea.roles.logs', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.roles.index');
    $breadcrumbs->push($role->title, route('adminarea.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.roles.logs', ['role' => $role]));
});

Breadcrumbs::register('adminarea.admins.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.admins'), route('adminarea.admins.index'));
});

Breadcrumbs::register('adminarea.admins.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.admins.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.admins.import'));
});

Breadcrumbs::register('adminarea.admins.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.admins.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.admins.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.admins.import.logs'));
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

Breadcrumbs::register('adminarea.managers.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.managers'), route('adminarea.managers.index'));
});

Breadcrumbs::register('adminarea.managers.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.managers.import'));
});

Breadcrumbs::register('adminarea.managers.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.managers.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.managers.import.logs'));
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

Breadcrumbs::register('adminarea.members.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.members'), route('adminarea.members.index'));
});

Breadcrumbs::register('adminarea.members.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.members.import'));
});

Breadcrumbs::register('adminarea.members.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.members.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.members.import.logs'));
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

Breadcrumbs::register('adminarea.guardians.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.guardians'), route('adminarea.guardians.index'));
});

Breadcrumbs::register('adminarea.guardians.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.guardians.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.guardians.import'));
});

Breadcrumbs::register('adminarea.guardians.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.guardians.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.guardians.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.guardians.import.logs'));
});

Breadcrumbs::register('adminarea.guardians.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.guardians.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_guardian'), route('adminarea.guardians.create'));
});

Breadcrumbs::register('adminarea.guardians.edit', function (BreadcrumbsGenerator $breadcrumbs, Guardian $guardian) {
    $breadcrumbs->parent('adminarea.guardians.index');
    $breadcrumbs->push($guardian->username, route('adminarea.guardians.edit', ['guardian' => $guardian]));
});

Breadcrumbs::register('adminarea.guardians.logs', function (BreadcrumbsGenerator $breadcrumbs, Guardian $guardian) {
    $breadcrumbs->parent('adminarea.guardians.index');
    $breadcrumbs->push($guardian->username, route('adminarea.guardians.edit', ['guardian' => $guardian]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.guardians.logs', ['guardian' => $guardian]));
});

Breadcrumbs::register('adminarea.abilities.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.abilities'), route('adminarea.abilities.index'));
});

Breadcrumbs::register('adminarea.abilities.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.abilities.import'));
});

Breadcrumbs::register('adminarea.abilities.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.abilities.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.abilities.import.logs'));
});

Breadcrumbs::register('adminarea.abilities.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_ability'), route('adminarea.abilities.create'));
});

Breadcrumbs::register('adminarea.abilities.edit', function (BreadcrumbsGenerator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push($ability->title, route('adminarea.abilities.edit', ['ability' => $ability]));
});

Breadcrumbs::register('adminarea.abilities.logs', function (BreadcrumbsGenerator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.abilities.index');
    $breadcrumbs->push($ability->title, route('adminarea.abilities.edit', ['ability' => $ability]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.abilities.logs', ['ability' => $ability]));
});

// Account Breadcrumbs
Breadcrumbs::register('adminarea.login', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('adminarea.login'));
});

Breadcrumbs::register('adminarea.passwordreset.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('adminarea.passwordreset.request'));
});

Breadcrumbs::register('adminarea.passwordreset.reset', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('adminarea.passwordreset.reset'));
});

Breadcrumbs::register('adminarea.verification.email.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('adminarea.verification.email.request'));
});

Breadcrumbs::register('adminarea.verification.phone.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('adminarea.verification.phone.request'));
});

Breadcrumbs::register('adminarea.verification.phone.verify', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('adminarea.verification.phone.verify'));
});

Breadcrumbs::register('adminarea.account', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('adminarea.account'));
});

Breadcrumbs::register('adminarea.account.settings', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('adminarea.account.settings'));
});

Breadcrumbs::register('adminarea.account.password', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('adminarea.account.password'));
});

Breadcrumbs::register('adminarea.account.attributes', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('adminarea.account.attributes'));
});

Breadcrumbs::register('adminarea.account.sessions', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('adminarea.account.sessions'));
});

Breadcrumbs::register('adminarea.account.twofactor', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('adminarea.account.twofactor'));
});

Breadcrumbs::register('adminarea.account.twofactor.totp.enable', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('adminarea.account.twofactor.totp.enable'));
});
