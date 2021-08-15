<?php

declare(strict_types=1);

use Cortex\Auth\Models\Role;
use Cortex\Auth\Models\Member;
use Cortex\Auth\Models\Manager;
use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('managerarea.home', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
});

Breadcrumbs::for('managerarea.cortex.auth.roles.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.app('request.tenant')->name, route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.roles'), route('managerarea.cortex.auth.roles.index'));
});

Breadcrumbs::for('managerarea.cortex.auth.roles.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.cortex.auth.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.cortex.auth.roles.import'));
});

Breadcrumbs::for('managerarea.cortex.auth.roles.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.cortex.auth.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.cortex.auth.roles.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.cortex.auth.roles.import.logs'));
});

Breadcrumbs::for('managerarea.cortex.auth.roles.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.cortex.auth.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_role'), route('managerarea.cortex.auth.roles.create'));
});

Breadcrumbs::for('managerarea.cortex.auth.roles.edit', function (Generator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('managerarea.cortex.auth.roles.index');
    $breadcrumbs->push(strip_tags($role->title), route('managerarea.cortex.auth.roles.edit', ['role' => $role]));
});

Breadcrumbs::for('managerarea.cortex.auth.roles.logs', function (Generator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('managerarea.cortex.auth.roles.index');
    $breadcrumbs->push(strip_tags($role->title), route('managerarea.cortex.auth.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.cortex.auth.roles.logs', ['role' => $role]));
});

Breadcrumbs::for('managerarea.cortex.auth.members.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.app('request.tenant')->name, route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.members'), route('managerarea.cortex.auth.members.index'));
});

Breadcrumbs::for('managerarea.cortex.auth.members.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.cortex.auth.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.cortex.auth.members.import'));
});

Breadcrumbs::for('managerarea.cortex.auth.members.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.cortex.auth.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.cortex.auth.members.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.cortex.auth.members.import.logs'));
});

Breadcrumbs::for('managerarea.cortex.auth.members.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.cortex.auth.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_member'), route('managerarea.cortex.auth.members.create'));
});

Breadcrumbs::for('managerarea.cortex.auth.members.edit', function (Generator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('managerarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('managerarea.cortex.auth.members.edit', ['member' => $member]));
});

Breadcrumbs::for('managerarea.cortex.auth.members.logs', function (Generator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('managerarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('managerarea.cortex.auth.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.cortex.auth.members.logs', ['member' => $member]));
});

Breadcrumbs::for('managerarea.cortex.auth.members.activities', function (Generator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('managerarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('managerarea.cortex.auth.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('managerarea.cortex.auth.members.activities', ['member' => $member]));
});

Breadcrumbs::for('managerarea.cortex.auth.members.attributes', function (Generator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('managerarea.cortex.auth.members.index');
    $breadcrumbs->push(strip_tags($member->username), route('managerarea.cortex.auth.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('managerarea.cortex.auth.members.attributes', ['member' => $member]));
});

Breadcrumbs::for('managerarea.cortex.auth.managers.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.app('request.tenant')->name, route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.managers'), route('managerarea.cortex.auth.managers.index'));
});

Breadcrumbs::for('managerarea.cortex.auth.managers.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.cortex.auth.managers.import'));
});

Breadcrumbs::for('managerarea.cortex.auth.managers.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.cortex.auth.managers.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.cortex.auth.managers.import.logs'));
});

Breadcrumbs::for('managerarea.cortex.auth.managers.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.cortex.auth.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_manager'), route('managerarea.cortex.auth.managers.create'));
});

Breadcrumbs::for('managerarea.cortex.auth.managers.edit', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('managerarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('managerarea.cortex.auth.managers.edit', ['manager' => $manager]));
});

Breadcrumbs::for('managerarea.cortex.auth.managers.logs', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('managerarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('managerarea.cortex.auth.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.cortex.auth.managers.logs', ['manager' => $manager]));
});

Breadcrumbs::for('managerarea.cortex.auth.managers.activities', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('managerarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('managerarea.cortex.auth.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('managerarea.cortex.auth.managers.activities', ['manager' => $manager]));
});

Breadcrumbs::for('managerarea.cortex.auth.managers.attributes', function (Generator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('managerarea.cortex.auth.managers.index');
    $breadcrumbs->push(strip_tags($manager->username), route('managerarea.cortex.auth.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('managerarea.cortex.auth.managers.attributes', ['manager' => $manager]));
});

// Account Breadcrumbs
Breadcrumbs::for('managerarea.cortex.auth.account.login', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('managerarea.cortex.auth.account.login'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.passwordreset.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('managerarea.cortex.auth.account.passwordreset.request'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.passwordreset.reset', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('managerarea.cortex.auth.account.passwordreset.reset'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.reauthentication.password', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.password'), route('managerarea.cortex.auth.account.reauthentication.password'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.reauthentication.twofactor', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.twofactor'), route('managerarea.cortex.auth.account.reauthentication.twofactor'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.verification.email.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('managerarea.cortex.auth.account.verification.email.request'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.verification.phone.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('managerarea.cortex.auth.account.verification.phone.request'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.verification.phone.verify', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('managerarea.cortex.auth.account.verification.phone.verify'));
});

Breadcrumbs::for('managerarea.cortex.auth.account', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('managerarea.cortex.auth.account'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.settings', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('managerarea.cortex.auth.account.settings'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.password', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('managerarea.cortex.auth.account.password'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.attributes', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('managerarea.cortex.auth.account.attributes'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.sessions', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('managerarea.cortex.auth.account.sessions'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.twofactor', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('managerarea.cortex.auth.account.twofactor'));
});

Breadcrumbs::for('managerarea.cortex.auth.account.twofactor.totp.enable', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('managerarea.cortex.auth.account.twofactor.totp.enable'));
});
