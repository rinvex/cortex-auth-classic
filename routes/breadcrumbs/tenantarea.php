<?php

declare(strict_types=1);

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('tenantarea.register', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.register'), route('tenantarea.register'));
});

Breadcrumbs::register('tenantarea.login', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('tenantarea.login'));
});

Breadcrumbs::register('tenantarea.passwordreset.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('tenantarea.passwordreset.request'));
});

Breadcrumbs::register('tenantarea.passwordreset.reset', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('tenantarea.passwordreset.reset'));
});

Breadcrumbs::register('tenantarea.verification.email.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('tenantarea.verification.email.request'));
});

Breadcrumbs::register('tenantarea.verification.phone.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('tenantarea.verification.phone.request'));
});

Breadcrumbs::register('tenantarea.verification.phone.verify', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('tenantarea.verification.phone.verify'));
});

Breadcrumbs::register('tenantarea.account', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('tenantarea.account'));
});

Breadcrumbs::register('tenantarea.account.settings', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('tenantarea.account.settings'));
});

Breadcrumbs::register('tenantarea.account.password', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('tenantarea.account.password'));
});

Breadcrumbs::register('tenantarea.account.attributes', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('tenantarea.account.attributes'));
});

Breadcrumbs::register('tenantarea.account.sessions', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('tenantarea.account.sessions'));
});

Breadcrumbs::register('tenantarea.account.twofactor', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('tenantarea.account.twofactor'));
});

Breadcrumbs::register('tenantarea.account.twofactor.totp.enable', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('tenantarea.account.twofactor.totp.enable'));
});
