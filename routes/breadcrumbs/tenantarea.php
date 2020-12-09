<?php

declare(strict_types=1);

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('tenantarea.cortex.auth.account.register', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.register'), route('tenantarea.cortex.auth.account.register'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.login', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('tenantarea.cortex.auth.account.login'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.passwordreset.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('tenantarea.cortex.auth.account.passwordreset.request'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.passwordreset.reset', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('tenantarea.cortex.auth.account.passwordreset.reset'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.reauthentication.password', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.password'), route('frontarea.cortex.auth.account.reauthentication.password'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.reauthentication.twofactor', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.twofactor'), route('frontarea.cortex.auth.account.reauthentication.twofactor'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.verification.email.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('tenantarea.cortex.auth.account.verification.email.request'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.verification.phone.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('tenantarea.cortex.auth.account.verification.phone.request'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.verification.phone.verify', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('tenantarea.cortex.auth.account.verification.phone.verify'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('tenantarea.cortex.auth.account'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.settings', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('tenantarea.cortex.auth.account.settings'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.password', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('tenantarea.cortex.auth.account.password'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.attributes', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('tenantarea.cortex.auth.account.attributes'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.sessions', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('tenantarea.cortex.auth.account.sessions'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.twofactor', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('tenantarea.cortex.auth.account.twofactor'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.twofactor.totp.enable', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('tenantarea.cortex.auth.account.twofactor.totp.enable'));
});
