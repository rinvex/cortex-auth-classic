<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Rinvex\Fort\Contracts\UserContract;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Adminarea\UsersDataTable;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Fort\Http\Requests\Adminarea\UserFormRequest;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return app(UsersDataTable::class)->with([
            'id' => 'cortex-fort-users',
            'phrase' => trans('cortex/fort::common.users'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * Display a listing of the resource logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function logs(UserContract $user)
    {
        return app(LogsDataTable::class)->with([
            'type' => 'users',
            'resource' => $user,
            'id' => 'cortex-fort-users-logs',
            'phrase' => trans('cortex/fort::common.users'),
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Display a listing of the resource activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function activities(UserContract $user)
    {
        return app(ActivitiesDataTable::class)->with([
            'type' => 'users',
            'resource' => $user,
            'id' => 'cortex-fort-users-activities',
            'phrase' => trans('cortex/fort::common.users'),
        ])->render('cortex/fort::adminarea.pages.user-activities');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\UserFormRequest $request
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
     * @param \Cortex\Fort\Http\Requests\Adminarea\UserFormRequest $request
     * @param \Rinvex\Fort\Contracts\UserContract                  $user
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
            'url' => route('adminarea.users.index'),
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

        $roles = $request->user($this->getGuard())->isSuperadmin()
            ? app('rinvex.fort.role')->all()->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->roles->pluck('name', 'id')->toArray();

        $abilities = $request->user($this->getGuard())->isSuperadmin()
            ? app('rinvex.fort.ability')->all()->groupBy('resource')->map->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->allAbilities->groupBy('resource')->map->pluck('name', 'id')->toArray();

        return view('cortex/fort::adminarea.forms.user', compact('user', 'abilities', 'roles', 'countries', 'languages', 'genders'));
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
            'url' => route('adminarea.users.index'),
            'with' => ['success' => trans('cortex/fort::messages.user.saved', ['username' => $user->username])],
        ]);
    }
}
