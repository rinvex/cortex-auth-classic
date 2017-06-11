<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Backend;

use Carbon\Carbon;
use Cortex\Fort\Models\Role;
use Cortex\Fort\Models\User;
use Illuminate\Http\Request;
use Cortex\Fort\Models\Ability;
use Cortex\Fort\DataTables\Backend\UsersDataTable;
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->process($request, new User());
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
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
        $roleList = Role::all()->pluck('name', 'id')->toArray();
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $abilityList = Ability::all()->groupBy('resource')->map(function ($item) {
            return $item->pluck('name', 'id');
        })->toArray();

        return view('cortex/fort::backend.forms.user', compact('user', 'abilityList', 'roleList', 'countries', 'languages'));
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
        $data['email_verified'] = $request->get('email_verified', false);
        $data['phone_verified'] = $request->get('phone_verified', false);

        // Remove empty password fields
        if (! $data['password']) {
            unset($data['password']);
        }

        // Update email verification date
        if ($user->email_verified && ! $user->email_verified_at) {
            $data['email_verified_at'] = Carbon::now();
        }

        // Update phone verification date
        if ($user->phone_verified && ! $user->phone_verified_at) {
            $data['phone_verified_at'] = Carbon::now();
        }

        // Save user
        $user->fill($data)->save();

        // Sync abilities
        if ($request->user($this->getGuard())->can('grant-abilities')) {
            $user->abilities()->sync((array) array_pull($data, 'abilityList'));
        }

        // Sync roles
        if ($request->user($this->getGuard())->can('assign-roles')) {
            $user->roles()->sync((array) array_pull($data, 'roleList'));
        }

        return intend([
            'url' => route('backend.users.index'),
            'with' => ['success' => trans('cortex/fort::messages.user.saved', ['userId' => $user->id])],
        ]);
    }
}
