<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator;

Breadcrumbs::register('tenantarea.cortex.auth.account.register', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.register'), route('tenantarea.cortex.auth.account.register'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.login', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('tenantarea.cortex.auth.account.login'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.passwordreset.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('tenantarea.cortex.auth.account.passwordreset.request'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.passwordreset.reset', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('tenantarea.cortex.auth.account.passwordreset.reset'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.reauthentication.password', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.password'), route('frontarea.cortex.auth.account.reauthentication.password'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.reauthentication.twofactor', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.twofactor'), route('frontarea.cortex.auth.account.reauthentication.twofactor'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.verification.email.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('tenantarea.cortex.auth.account.verification.email.request'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.verification.phone.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('tenantarea.cortex.auth.account.verification.phone.request'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.verification.phone.verify', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('tenantarea.cortex.auth.account.verification.phone.verify'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('tenantarea.cortex.auth.account'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.settings', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('tenantarea.cortex.auth.account.settings'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.password', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('tenantarea.cortex.auth.account.password'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.attributes', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('tenantarea.cortex.auth.account.attributes'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.sessions', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('tenantarea.cortex.auth.account.sessions'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.twofactor', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('tenantarea.cortex.auth.account.twofactor'));
});

Breadcrumbs::register('tenantarea.cortex.auth.account.twofactor.totp.enable', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('tenantarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('tenantarea.cortex.auth.account.twofactor.totp.enable'));
});
