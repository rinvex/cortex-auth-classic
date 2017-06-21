<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Backend;

use Cortex\Fort\Models\Role;
use Cortex\Fort\Models\User;
use Illuminate\Http\Request;
use Cortex\Fort\Models\Ability;
use Cortex\Fort\DataTables\Backend\UsersDataTable;
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
        return app(UsersDataTable::class)->render('cortex/foundation::backend.partials.datatable', ['resource' => 'cortex/fort::common.users']);
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
        return $this->process($request, new User());
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Backend\UserFormRequest $request
     * @param \Cortex\Fort\Models\User                           $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, User $user)
    {
        return $this->process($request, $user);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(User $user)
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
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function form(User $user)
    {
        $countries = countries();
        $roles = Role::all()->pluck('name', 'id')->toArray();
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['m' => trans('cortex/fort::common.male'), 'f' => trans('cortex/fort::common.female')];
        $abilities = Ability::all()->groupBy('resource')->map(function ($item) {
            return $item->pluck('name', 'id');
        })->toArray();

        return view('cortex/fort::backend.forms.user', compact('user', 'abilities', 'roles', 'countries', 'languages', 'genders'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(Request $request, User $user)
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
