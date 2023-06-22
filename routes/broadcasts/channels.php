<?php

declare(strict_types=1);

use Illuminate\Contracts\Auth\Access\Authorizable;

Broadcast::channel('cortex.auth.abilities.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.ability'));
});

Broadcast::channel('cortex.auth.roles.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.role'));
});

Broadcast::channel('cortex.auth.admins.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.admin'));
});

Broadcast::channel('cortex.auth.members.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.member'));
});

Broadcast::channel('cortex.auth.guardians.index', function (Authorizable $user) {
    return $user->can('list', app('cortex.auth.guardian'));
});
