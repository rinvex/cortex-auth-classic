<?php

declare(strict_types=1);

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('frontarea.register.member', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.register'), route('frontarea.register.member'));
});

Breadcrumbs::register('frontarea.register.tenant', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.register'), route('frontarea.register.tenant'));
});

Breadcrumbs::register('frontarea.login', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('frontarea.login'));
});

Breadcrumbs::register('frontarea.passwordreset.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('frontarea.passwordreset.request'));
});

Breadcrumbs::register('frontarea.passwordreset.reset', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('frontarea.passwordreset.reset'));
});

Breadcrumbs::register('frontarea.reauthentication.password', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.password'), route('frontarea.reauthentication.password'));
});

Breadcrumbs::register('frontarea.reauthentication.twofactor', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.twofactor'), route('frontarea.reauthentication.twofactor'));
});

Breadcrumbs::register('frontarea.verification.email.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('frontarea.verification.email.request'));
});

Breadcrumbs::register('frontarea.verification.phone.request', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('frontarea.verification.phone.request'));
});

Breadcrumbs::register('frontarea.verification.phone.verify', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('frontarea.verification.phone.verify'));
});

Breadcrumbs::register('frontarea.account', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('frontarea.account'));
});

Breadcrumbs::register('frontarea.account.settings', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('frontarea.account.settings'));
});

Breadcrumbs::register('frontarea.account.password', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('frontarea.account.password'));
});

Breadcrumbs::register('frontarea.account.attributes', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('frontarea.account.attributes'));
});

Breadcrumbs::register('frontarea.account.sessions', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('frontarea.account.sessions'));
});

Breadcrumbs::register('frontarea.account.twofactor', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('frontarea.account.twofactor'));
});

Breadcrumbs::register('frontarea.account.twofactor.totp.enable', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('frontarea.account.twofactor.totp.enable'));
});
