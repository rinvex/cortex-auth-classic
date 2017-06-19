<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Backend;

use Carbon\Carbon;
use Cortex\Fort\Models\Role;
use Cortex\Fort\Models\User;
use Cortex\Fort\Models\Ability;
use Illuminate\Support\Facades\DB;
use Rinvex\Fort\Models\Persistence;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class DashboardController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'dashboard';

    /**
     * {@inheritdoc}
     */
    protected $resourceAbilityMap = ['home' => 'access'];

    /**
     * Show the dashboard home.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        // Get statistics
        $stats = [
            'abilities' => ['route' => route('backend.abilities.index'), 'count' => Ability::count()],
            'roles' => ['route' => route('backend.roles.index'), 'count' => Role::count()],
            'users' => ['route' => route('backend.users.index'), 'count' => User::count()],
        ];

        // Get online users
        $onlineInterval = Carbon::now()->subMinutes(config('rinvex.fort.online.interval'));
        $persistences = Persistence::groupBy(['user_id'])
            ->with(['user'])
            ->where('attempt', '=', 0)
            ->where('updated_at', '>', $onlineInterval)
            ->get(['user_id', DB::raw('MAX(updated_at) as updated_at')]);

        return view('cortex/fort::backend.pages.home', compact('persistences', 'stats'));
    }
}
