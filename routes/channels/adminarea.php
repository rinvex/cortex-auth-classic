<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Access\Authorizable;

Broadcast::channel('adminarea-abilities-index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.ability'));
}, ['guards' => ['admin']]);

Broadcast::channel('adminarea-roles-index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.role'));
}, ['guards' => ['admin']]);

Broadcast::channel('adminarea-admins-index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.admin'));
}, ['guards' => ['admin']]);

Broadcast::channel('adminarea-managers-index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.manager'));
}, ['guards' => ['admin']]);

Broadcast::channel('adminarea-members-index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.member'));
}, ['guards' => ['admin']]);

Broadcast::channel('adminarea-guardians-index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.guardian'));
}, ['guards' => ['admin']]);
