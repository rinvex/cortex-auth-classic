<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Rinvex\Fort\Contracts\UserContract;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Backend\UsersDataTable;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Fort\Http\Requests\Backend\UserFormRequest;
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
        ])->render('cortex/foundation::backend.pages.datatable');
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
        ])->render('cortex/foundation::backend.pages.datatable-logs');
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
        ])->render('cortex/foundation::backend.pages.datatable-activities');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Backend\UserFormRequest $request
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
     * @param \Cortex\Fort\Http\Requests\Backend\UserFormRequest $request
     * @param \Rinvex\Fort\Contracts\UserContract                $user
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
            'url' => route('backend.users.index'),
            'with' => ['warning' => trans('cortex/fort::messages.user.deleted', ['userId' => $user->id])],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return \Illuminate\Http\Response
     */
    public function form(UserContract $user)
    {
        $countries = countries();
        $roles = app('rinvex.fort.role')->all()->pluck('name', 'id')->toArray();
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['m' => trans('cortex/fort::common.male'), 'f' => trans('cortex/fort::common.female')];
        $abilities = app('rinvex.fort.ability')->all()->groupBy('resource')->map->pluck('name', 'id')->toArray();

        return view('cortex/fort::backend.forms.user', compact('user', 'abilities', 'roles', 'countries', 'languages', 'genders'));
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
            'url' => route('backend.users.index'),
            'with' => ['success' => trans('cortex/fort::messages.user.saved', ['userId' => $user->id])],
        ]);
    }
}
