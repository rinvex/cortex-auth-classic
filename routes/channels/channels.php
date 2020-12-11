<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Access\Authorizable;

Broadcast::channel('cortex.auth.abilities.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.ability'));
}, ['guards' => ['admin']]);

Broadcast::channel('cortex.auth.roles.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.role'));
}, ['guards' => ['admin']]);

Broadcast::channel('cortex.auth.admins.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.admin'));
}, ['guards' => ['admin']]);

Broadcast::channel('cortex.auth.managers.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.manager'));
}, ['guards' => ['admin']]);

Broadcast::channel('cortex.auth.members.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.member'));
}, ['guards' => ['admin']]);

Broadcast::channel('cortex.auth.guardians.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.guardian'));
}, ['guards' => ['admin']]);
