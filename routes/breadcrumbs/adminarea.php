<?php

declare(strict_types=1);

use Cortex\Auth\Models\Role;
use Cortex\Auth\Models\Admin;
use Cortex\Auth\Models\Member;
use Cortex\Auth\Models\Ability;
use Cortex\Auth\Models\Manager;
use Cortex\Auth\Models\Guardian;
use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('adminarea.home', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
});

Breadcrumbs::for('adminarea.cortex.auth.roles.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.roles'), route('adminarea.cortex.auth.roles.index'));
});

Breadcrumbs::for('adminarea.cortex.auth.roles.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.roles.import'));
});

Breadcrumbs::for('adminarea.cortex.auth.roles.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.roles.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.roles.import.logs'));
});

Breadcrumbs::for('adminarea.cortex.auth.roles.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_role'), route('adminarea.cortex.auth.roles.create'));
});

Breadcrumbs::for('adminarea.cortex.auth.roles.edit', function (Generator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(strip_tags($role->title), route('adminarea.cortex.auth.roles.edit', ['role' => $role]));
});

Breadcrumbs::for('adminarea.cortex.auth.roles.logs', function (Generator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('adminarea.cortex.auth.roles.index');
    $breadcrumbs->push(strip_tags($role->title), route('adminarea.cortex.auth.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.roles.logs', ['role' => $role]));
});

Breadcrumbs::for('adminarea.cortex.auth.admins.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.admins'), route('adminarea.cortex.auth.admins.index'));
});

Breadcrumbs::for('adminarea.cortex.auth.admins.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.admins.import'));
});

Breadcrumbs::for('adminarea.cortex.auth.admins.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.admins.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.admins.import.logs'));
});

Breadcrumbs::for('adminarea.cortex.auth.admins.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_admin'), route('adminarea.cortex.auth.admins.create'));
});

Breadcrumbs::for('adminarea.cortex.auth.admins.edit', function (Generator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(strip_tags($admin->username), route('adminarea.cortex.auth.admins.edit', ['admin' => $admin]));
});

Breadcrumbs::for('adminarea.cortex.auth.admins.logs', function (Generator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(strip_tags($admin->username), route('adminarea.cortex.auth.admins.edit', ['admin' => $admin]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.admins.logs', ['admin' => $admin]));
});

Breadcrumbs::for('adminarea.cortex.auth.admins.activities', function (Generator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(strip_tags($admin->username), route('adminarea.cortex.auth.admins.edit', ['admin' => $admin]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.cortex.auth.admins.activities', ['admin' => $admin]));
});

Breadcrumbs::for('adminarea.cortex.auth.admins.attributes', function (Generator $breadcrumbs, Admin $admin) {
    $breadcrumbs->parent('adminarea.cortex.auth.admins.index');
    $breadcrumbs->push(strip_tags($admin->username), route('adminarea.cortex.auth.admins.edit', ['admin' => $admin]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('adminarea.cortex.auth.admins.attributes', ['admin' => $admin]));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.managers'), route('adminarea.cortex.auth.managers.index'));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.managers.import'));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.managers.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.managers.import.logs'));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_manager'), route('adminarea.cortex.auth.managers.create'));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.edit', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('adminarea.cortex.auth.managers.edit', ['manager' => $manager]));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.logs', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('adminarea.cortex.auth.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.managers.logs', ['manager' => $manager]));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.activities', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('adminarea.cortex.auth.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.cortex.auth.managers.activities', ['manager' => $manager]));
});

Breadcrumbs::for('adminarea.cortex.auth.managers.attributes', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('adminarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('adminarea.cortex.auth.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('adminarea.cortex.auth.managers.attributes', ['manager' => $manager]));
});

Breadcrumbs::for('adminarea.cortex.auth.members.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.members'), route('adminarea.cortex.auth.members.index'));
});

Breadcrumbs::for('adminarea.cortex.auth.members.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.members.import'));
});

Breadcrumbs::for('adminarea.cortex.auth.members.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.members.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.members.import.logs'));
});

Breadcrumbs::for('adminarea.cortex.auth.members.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_member'), route('adminarea.cortex.auth.members.create'));
});

Breadcrumbs::for('adminarea.cortex.auth.members.edit', function (Generator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('adminarea.cortex.auth.members.edit', ['member' => $member]));
});

Breadcrumbs::for('adminarea.cortex.auth.members.logs', function (Generator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('adminarea.cortex.auth.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.members.logs', ['member' => $member]));
});

Breadcrumbs::for('adminarea.cortex.auth.members.activities', function (Generator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('adminarea.cortex.auth.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('adminarea.cortex.auth.members.activities', ['member' => $member]));
});

Breadcrumbs::for('adminarea.cortex.auth.members.attributes', function (Generator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('adminarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('adminarea.cortex.auth.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('adminarea.cortex.auth.members.attributes', ['member' => $member]));
});

Breadcrumbs::for('adminarea.cortex.auth.guardians.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.guardians'), route('adminarea.cortex.auth.guardians.index'));
});

Breadcrumbs::for('adminarea.cortex.auth.guardians.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.guardians.import'));
});

Breadcrumbs::for('adminarea.cortex.auth.guardians.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.guardians.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.guardians.import.logs'));
});

Breadcrumbs::for('adminarea.cortex.auth.guardians.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_guardian'), route('adminarea.cortex.auth.guardians.create'));
});

Breadcrumbs::for('adminarea.cortex.auth.guardians.edit', function (Generator $breadcrumbs, Guardian $guardian) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(strip_tags($guardian->username), route('adminarea.cortex.auth.guardians.edit', ['guardian' => $guardian]));
});

Breadcrumbs::for('adminarea.cortex.auth.guardians.logs', function (Generator $breadcrumbs, Guardian $guardian) {
    $breadcrumbs->parent('adminarea.cortex.auth.guardians.index');
    $breadcrumbs->push(strip_tags($guardian->username), route('adminarea.cortex.auth.guardians.edit', ['guardian' => $guardian]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.guardians.logs', ['guardian' => $guardian]));
});

Breadcrumbs::for('adminarea.cortex.auth.abilities.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.abilities'), route('adminarea.cortex.auth.abilities.index'));
});

Breadcrumbs::for('adminarea.cortex.auth.abilities.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.abilities.import'));
});

Breadcrumbs::for('adminarea.cortex.auth.abilities.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('adminarea.cortex.auth.abilities.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.abilities.import.logs'));
});

Breadcrumbs::for('adminarea.cortex.auth.abilities.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_ability'), route('adminarea.cortex.auth.abilities.create'));
});

Breadcrumbs::for('adminarea.cortex.auth.abilities.edit', function (Generator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(strip_tags($ability->title), route('adminarea.cortex.auth.abilities.edit', ['ability' => $ability]));
});

Breadcrumbs::for('adminarea.cortex.auth.abilities.logs', function (Generator $breadcrumbs, Ability $ability) {
    $breadcrumbs->parent('adminarea.cortex.auth.abilities.index');
    $breadcrumbs->push(strip_tags($ability->title), route('adminarea.cortex.auth.abilities.edit', ['ability' => $ability]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('adminarea.cortex.auth.abilities.logs', ['ability' => $ability]));
});

// Account Breadcrumbs
Breadcrumbs::for('adminarea.cortex.auth.account.login', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('adminarea.cortex.auth.account.login'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.passwordreset.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('adminarea.cortex.auth.account.passwordreset.request'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.passwordreset.reset', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('adminarea.cortex.auth.account.passwordreset.reset'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.reauthentication.password', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.password'), route('frontarea.cortex.auth.account.reauthentication.password'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.reauthentication.twofactor', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.twofactor'), route('frontarea.cortex.auth.account.reauthentication.twofactor'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.verification.email.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('adminarea.cortex.auth.account.verification.email.request'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.verification.phone.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('adminarea.cortex.auth.account.verification.phone.request'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.verification.phone.verify', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('adminarea.cortex.auth.account.verification.phone.verify'));
});

Breadcrumbs::for('adminarea.cortex.auth.account', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('adminarea.cortex.auth.account'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.settings', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('adminarea.cortex.auth.account.settings'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.password', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('adminarea.cortex.auth.account.password'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.attributes', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('adminarea.cortex.auth.account.attributes'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.sessions', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('adminarea.cortex.auth.account.sessions'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.twofactor', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('adminarea.cortex.auth.account.twofactor'));
});

Breadcrumbs::for('adminarea.cortex.auth.account.twofactor.totp.enable', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('adminarea.cortex.auth.account.twofactor.totp.enable'));
});
