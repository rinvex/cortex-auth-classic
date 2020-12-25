<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::register('frontarea.cortex.auth.account.register.member', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.register'), route('frontarea.cortex.auth.account.register.member'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.register.tenant', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.register'), route('frontarea.cortex.auth.account.register.tenant'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.login', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.login'), route('frontarea.cortex.auth.account.login'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.passwordreset.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset_request'), route('frontarea.cortex.auth.account.passwordreset.request'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.passwordreset.reset', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.passwordreset'), route('frontarea.cortex.auth.account.passwordreset.reset'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.reauthentication.password', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.password'), route('frontarea.cortex.auth.account.reauthentication.password'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.reauthentication.twofactor', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.reauthentication.twofactor'), route('frontarea.cortex.auth.account.reauthentication.twofactor'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.verification.email.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_email_request'), route('frontarea.cortex.auth.account.verification.email.request'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.verification.phone.request', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verification_phone_request'), route('frontarea.cortex.auth.account.verification.phone.request'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.verification.phone.verify', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.verify_phone'), route('frontarea.cortex.auth.account.verification.phone.verify'));
});

Breadcrumbs::register('frontarea.cortex.auth.account', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account'), route('frontarea.cortex.auth.account'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.settings', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_settings'), route('frontarea.cortex.auth.account.settings'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.password', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_password'), route('frontarea.cortex.auth.account.password'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.attributes', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_attributes'), route('frontarea.cortex.auth.account.attributes'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.sessions', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_sessions'), route('frontarea.cortex.auth.account.sessions'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.twofactor', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::common.account_twofactor'), route('frontarea.cortex.auth.account.twofactor'));
});

Breadcrumbs::register('frontarea.cortex.auth.account.twofactor.totp.enable', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
    $breadcrumbs->push(trans('cortex/auth::twofactor.configure'), route('frontarea.cortex.auth.account.twofactor.totp.enable'));
});
