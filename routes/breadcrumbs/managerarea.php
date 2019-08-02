<?php

declare(strict_types=1);

use Cortex\Auth\Models\Role;
use Cortex\Auth\Models\Member;
use Cortex\Auth\Models\Manager;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('managerarea.home', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
});

Breadcrumbs::register('managerarea.roles.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('rinvex.tenants.active')->name, route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.roles'), route('managerarea.roles.index'));
});

Breadcrumbs::register('managerarea.roles.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.roles.import'));
});

Breadcrumbs::register('managerarea.roles.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.roles.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.roles.import.logs'));
});

Breadcrumbs::register('managerarea.roles.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.roles.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_role'), route('managerarea.roles.create'));
});

Breadcrumbs::register('managerarea.roles.edit', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('managerarea.roles.index');
    $breadcrumbs->push($role->title, route('managerarea.roles.edit', ['role' => $role]));
});

Breadcrumbs::register('managerarea.roles.logs', function (BreadcrumbsGenerator $breadcrumbs, Role $role) {
    $breadcrumbs->parent('managerarea.roles.index');
    $breadcrumbs->push($role->title, route('managerarea.roles.edit', ['role' => $role]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.roles.logs', ['role' => $role]));
});

Breadcrumbs::register('managerarea.members.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('rinvex.tenants.active')->name, route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.members'), route('managerarea.members.index'));
});

Breadcrumbs::register('managerarea.members.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.members.import'));
});

Breadcrumbs::register('managerarea.members.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.members.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.members.import.logs'));
});

Breadcrumbs::register('managerarea.members.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.members.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_member'), route('managerarea.members.create'));
});

Breadcrumbs::register('managerarea.members.edit', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('managerarea.members.index');
    $breadcrumbs->push($member->username, route('managerarea.members.edit', ['member' => $member]));
});

Breadcrumbs::register('managerarea.members.logs', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('managerarea.members.index');
    $breadcrumbs->push($member->username, route('managerarea.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.members.logs', ['member' => $member]));
});

Breadcrumbs::register('managerarea.members.activities', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('managerarea.members.index');
    $breadcrumbs->push($member->username, route('managerarea.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('managerarea.members.activities', ['member' => $member]));
});

Breadcrumbs::register('managerarea.members.attributes', function (BreadcrumbsGenerator $breadcrumbs, Member $member) {
    $breadcrumbs->parent('managerarea.members.index');
    $breadcrumbs->push($member->username, route('managerarea.members.edit', ['member' => $member]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('managerarea.members.attributes', ['member' => $member]));
});

Breadcrumbs::register('managerarea.managers.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('rinvex.tenants.active')->name, route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.managers'), route('managerarea.managers.index'));
});

Breadcrumbs::register('managerarea.managers.import', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.managers.import'));
});

Breadcrumbs::register('managerarea.managers.import.logs', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.import'), route('managerarea.managers.import'));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.managers.import.logs'));
});

Breadcrumbs::register('managerarea.managers.create', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->parent('managerarea.managers.index');
    $breadcrumbs->push(trans('cortex/auth::common.create_manager'), route('managerarea.managers.create'));
});

Breadcrumbs::register('managerarea.managers.edit', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('managerarea.managers.index');
    $breadcrumbs->push($manager->username, route('managerarea.managers.edit', ['manager' => $manager]));
});

Breadcrumbs::register('managerarea.managers.logs', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('managerarea.managers.index');
    $breadcrumbs->push($manager->username, route('managerarea.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.logs'), route('managerarea.managers.logs', ['manager' => $manager]));
});

Breadcrumbs::register('managerarea.managers.activities', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('managerarea.managers.index');
    $breadcrumbs->push($manager->username, route('managerarea.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.activities'), route('managerarea.managers.activities', ['manager' => $manager]));
});

Breadcrumbs::register('managerarea.managers.attributes', function (BreadcrumbsGenerator $breadcrumbs, Manager $manager) {
    $breadcrumbs->parent('managerarea.managers.index');
    $breadcrumbs->push($manager->username, route('managerarea.managers.edit', ['manager' => $manager]));
    $breadcrumbs->push(trans('cortex/auth::common.attributes'), route('managerarea.managers.attributes', ['manager' => $manager]));
});

// Account Breadcrumbs
Breadcrumbs::register('managerarea.login', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('managerarea.login'));
});

Breadcrumbs::register('managerarea.passwordreset.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('managerarea.passwordreset.request'));
});

Breadcrumbs::register('managerarea.passwordreset.reset', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('managerarea.passwordreset.reset'));
});

Breadcrumbs::register('managerarea.verification.email.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('managerarea.verification.email.request'));
});

Breadcrumbs::register('managerarea.verification.phone.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('managerarea.verification.phone.request'));
});

Breadcrumbs::register('managerarea.verification.phone.verify', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('managerarea.verification.phone.verify'));
});

Breadcrumbs::register('managerarea.account', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('managerarea.account'));
});

Breadcrumbs::register('managerarea.account.settings', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('managerarea.account.settings'));
});

Breadcrumbs::register('managerarea.account.password', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('managerarea.account.password'));
});

Breadcrumbs::register('managerarea.account.attributes', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('managerarea.account.attributes'));
});

Breadcrumbs::register('managerarea.account.sessions', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('managerarea.account.sessions'));
});

Breadcrumbs::register('managerarea.account.twofactor', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('managerarea.account.twofactor'));
});

Breadcrumbs::register('managerarea.account.twofactor.totp.enable', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('managerarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('managerarea.account.twofactor.totp.enable'));
});
