<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Tenantarea;

use Illuminate\Http\Request;
use Rinvex\Fort\Contracts\UserContract;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Tenantarea\UsersDataTable;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Fort\Http\Requests\Tenantarea\UserFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class UsersController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'users';

    /**
     * Display a listing of the resource.
     *
     * @param \Cortex\Fort\DataTables\Tenantarea\UsersDataTable $usersDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(UsersDataTable $usersDataTable)
    {
        return $usersDataTable->with([
            'id' => 'cortex-users',
            'phrase' => trans('cortex/fort::common.users'),
        ])->render('cortex/foundation::tenantarea.pages.datatable');
    }

    /**
     * Display a listing of the resource logs.
     *
     * @param \Rinvex\Fort\Contracts\UserContract         $user
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function logs(UserContract $user, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'tab' => 'logs',
            'type' => 'users',
            'resource' => $user,
            'id' => 'cortex-users-logs',
            'phrase' => trans('cortex/fort::common.users'),
        ])->render('cortex/fort::tenantarea.pages.user-logs');
    }

    /**
     * Display a listing of the resource activities.
     *
     * @param \Rinvex\Fort\Contracts\UserContract               $user
     * @param \Cortex\Foundation\DataTables\ActivitiesDataTable $activitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function activities(UserContract $user, ActivitiesDataTable $activitiesDataTable)
    {
        return $activitiesDataTable->with([
            'tab' => 'activities',
            'type' => 'users',
            'resource' => $user,
            'id' => 'cortex-users-activities',
            'phrase' => trans('cortex/fort::common.users'),
        ])->render('cortex/fort::tenantarea.pages.user-activities');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\UserFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        return $this->process($request, app('rinvex.fort.user'));
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\UserFormRequest $request
     * @param \Rinvex\Fort\Contracts\UserContract                   $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, UserContract $user)
    {
        return $this->process($request, $user);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(UserContract $user)
    {
        $user->delete();

        return intend([
            'url' => route('tenantarea.users.index'),
            'with' => ['warning' => trans('cortex/fort::messages.user.deleted', ['username' => $user->username])],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Request $request, UserContract $user)
    {
        $countries = countries();
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['m' => trans('cortex/fort::common.male'), 'f' => trans('cortex/fort::common.female')];
        $owner = optional(optional(config('rinvex.tenants.tenant.active'))->owner)->id;

        $roles = $request->user($this->getGuard())->id === $owner
            ? app('rinvex.fort.role')->all()->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->roles->pluck('name', 'id')->toArray();

        $abilities = $request->user($this->getGuard())->id === $owner
            ? app('rinvex.fort.role')->forAllTenants()->where('slug', 'manager')->first()->abilities->groupBy('resource')->map->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->allAbilities->groupBy('resource')->map->pluck('name', 'id')->toArray();

        return view('cortex/fort::tenantarea.forms.user', compact('user', 'abilities', 'roles', 'countries', 'languages', 'genders'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(Request $request, UserContract $user)
    {
        // Prepare required input fields
        $data = $request->all();

        // Save user
        $user->fill($data)->save();

        return intend([
            'url' => route('tenantarea.users.index'),
            'with' => ['success' => trans('cortex/fort::messages.user.saved', ['username' => $user->username])],
        ]);
    }
}
