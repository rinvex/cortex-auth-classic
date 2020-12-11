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

Breadcrumbs::register('adminarea.cortex.auth.roles.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.roles'), route('adminarea.cortex.auth.roles.index'));
});

Breadcrumbs::register('adminarea.cortex.auth.roles.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.roles.import'));
});

Breadcrumbs::register('adminarea.cortex.auth.roles.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.roles.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.roles.import.logs'));
});

Breadcrumbs::register('adminarea.cortex.auth.roles.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_role'), route('adminarea.cortex.auth.roles.create'));
});

Breadcrumbs::register('adminarea.cortex.auth.roles.edit', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(strip_tags($role->title), route('adminarea.cortex.auth.roles.edit', ['role' => $role]));
});

Breadcrumbs::register('adminarea.cortex.auth.roles.logs', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(strip_tags($role->title), route('adminarea.cortex.auth.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.roles.logs', ['role' => $role]));
});

Breadcrumbs::register('adminarea.cortex.auth.admins.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.admins'), route('adminarea.cortex.auth.admins.index'));
});

Breadcrumbs::register('adminarea.cortex.auth.admins.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.admins.import'));
});

Breadcrumbs::register('adminarea.cortex.auth.admins.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.admins.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.admins.import.logs'));
});

Breadcrumbs::register('adminarea.cortex.auth.admins.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_admin'), route('adminarea.cortex.auth.admins.create'));
});

Breadcrumbs::register('adminarea.cortex.auth.admins.edit', function (BreadcrumbsGenerator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(strip_tags($admin->username), route('adminarea.cortex.auth.admins.edit', ['admin' => $admin]));
});

Breadcrumbs::register('adminarea.cortex.auth.admins.logs', function (BreadcrumbsGenerator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(strip_tags($admin->username), route('adminarea.cortex.auth.admins.edit', ['admin' => $admin]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.admins.logs', ['admin' => $admin]));
});

Breadcrumbs::register('adminarea.cortex.auth.admins.activities', function (BreadcrumbsGenerator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(strip_tags($admin->username), route('adminarea.cortex.auth.admins.edit', ['admin' => $admin]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.cortex.auth.admins.activities', ['admin' => $admin]));
});

Breadcrumbs::register('adminarea.cortex.auth.admins.attributes', function (BreadcrumbsGenerator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(strip_tags($admin->username), route('adminarea.cortex.auth.admins.edit', ['admin' => $admin]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('adminarea.cortex.auth.admins.attributes', ['admin' => $admin]));
});

Breadcrumbs::register('adminarea.cortex.auth.managers.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.managers'), route('adminarea.cortex.auth.managers.index'));
});

Breadcrumbs::register('adminarea.cortex.auth.managers.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.managers.import'));
});

Breadcrumbs::register('adminarea.cortex.auth.managers.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.managers.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.managers.import.logs'));
});

Breadcrumbs::register('adminarea.cortex.auth.managers.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_manager'), route('adminarea.cortex.auth.managers.create'));
});

Breadcrumbs::register('adminarea.cortex.auth.managers.edit', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('adminarea.cortex.auth.managers.edit', ['manager' => $manager]));
});

Breadcrumbs::register('adminarea.cortex.auth.managers.logs', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('adminarea.cortex.auth.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.managers.logs', ['manager' => $manager]));
});

Breadcrumbs::register('adminarea.cortex.auth.managers.activities', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('adminarea.cortex.auth.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.cortex.auth.managers.activities', ['manager' => $manager]));
});

Breadcrumbs::register('adminarea.cortex.auth.managers.attributes', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('adminarea.cortex.auth.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('adminarea.cortex.auth.managers.attributes', ['manager' => $manager]));
});

Breadcrumbs::register('adminarea.cortex.auth.members.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.members'), route('adminarea.cortex.auth.members.index'));
});

Breadcrumbs::register('adminarea.cortex.auth.members.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.members.import'));
});

Breadcrumbs::register('adminarea.cortex.auth.members.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.members.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.members.import.logs'));
});

Breadcrumbs::register('adminarea.cortex.auth.members.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_member'), route('adminarea.cortex.auth.members.create'));
});

Breadcrumbs::register('adminarea.cortex.auth.members.edit', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('adminarea.cortex.auth.members.edit', ['member' => $member]));
});

Breadcrumbs::register('adminarea.cortex.auth.members.logs', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('adminarea.cortex.auth.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.members.logs', ['member' => $member]));
});

Breadcrumbs::register('adminarea.cortex.auth.members.activities', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('adminarea.cortex.auth.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.cortex.auth.members.activities', ['member' => $member]));
});

Breadcrumbs::register('adminarea.cortex.auth.members.attributes', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('adminarea.cortex.auth.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('adminarea.cortex.auth.members.attributes', ['member' => $member]));
});

Breadcrumbs::register('adminarea.cortex.auth.guardians.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.guardians'), route('adminarea.cortex.auth.guardians.index'));
});

Breadcrumbs::register('adminarea.cortex.auth.guardians.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.guardians.import'));
});

Breadcrumbs::register('adminarea.cortex.auth.guardians.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.guardians.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.guardians.import.logs'));
});

Breadcrumbs::register('adminarea.cortex.auth.guardians.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_guardian'), route('adminarea.cortex.auth.guardians.create'));
});

Breadcrumbs::register('adminarea.cortex.auth.guardians.edit', function (BreadcrumbsGenerator $breadcrumbs, Guardian $guardian) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(strip_tags($guardian->username), route('adminarea.cortex.auth.guardians.edit', ['guardian' => $guardian]));
});

Breadcrumbs::register('adminarea.cortex.auth.guardians.logs', function (BreadcrumbsGenerator $breadcrumbs, Guardian $guardian) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(strip_tags($guardian->username), route('adminarea.cortex.auth.guardians.edit', ['guardian' => $guardian]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.guardians.logs', ['guardian' => $guardian]));
});

Breadcrumbs::register('adminarea.cortex.auth.abilities.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.abilities'), route('adminarea.cortex.auth.abilities.index'));
});

Breadcrumbs::register('adminarea.cortex.auth.abilities.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.abilities.import'));
});

Breadcrumbs::register('adminarea.cortex.auth.abilities.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.abilities.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.abilities.import.logs'));
});

Breadcrumbs::register('adminarea.cortex.auth.abilities.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_ability'), route('adminarea.cortex.auth.abilities.create'));
});

Breadcrumbs::register('adminarea.cortex.auth.abilities.edit', function (BreadcrumbsGenerator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(strip_tags($ability->title), route('adminarea.cortex.auth.abilities.edit', ['ability' => $ability]));
});

Breadcrumbs::register('adminarea.cortex.auth.abilities.logs', function (BreadcrumbsGenerator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(strip_tags($ability->title), route('adminarea.cortex.auth.abilities.edit', ['ability' => $ability]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.abilities.logs', ['ability' => $ability]));
});

// Account Breadcrumbs
Breadcrumbs::register('adminarea.cortex.auth.account.login', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('adminarea.cortex.auth.account.login'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.passwordreset.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('adminarea.cortex.auth.account.passwordreset.request'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.passwordreset.reset', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('adminarea.cortex.auth.account.passwordreset.reset'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.reauthentication.password', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.password'), route('frontarea.cortex.auth.account.reauthentication.password'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.reauthentication.twofactor', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.twofactor'), route('frontarea.cortex.auth.account.reauthentication.twofactor'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.verification.email.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('adminarea.cortex.auth.account.verification.email.request'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.verification.phone.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('adminarea.cortex.auth.account.verification.phone.request'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.verification.phone.verify', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('adminarea.cortex.auth.account.verification.phone.verify'));
});

Breadcrumbs::register('adminarea.cortex.auth.account', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('adminarea.cortex.auth.account'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.settings', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('adminarea.cortex.auth.account.settings'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.password', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('adminarea.cortex.auth.account.password'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.attributes', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('adminarea.cortex.auth.account.attributes'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.sessions', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('adminarea.cortex.auth.account.sessions'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.twofactor', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('adminarea.cortex.auth.account.twofactor'));
});

Breadcrumbs::register('adminarea.cortex.auth.account.twofactor.totp.enable', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('adminarea.cortex.auth.account.twofactor.totp.enable'));
});
