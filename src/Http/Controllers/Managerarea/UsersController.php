<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Managerarea;

use Illuminate\Http\Request;
use Cortex\Fort\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Fort\DataTables\Managerarea\UsersDataTable;
use Cortex\Fort\Http\Requests\Managerarea\UserFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Fort\Http\Requests\Managerarea\UserAttributesFormRequest;

class UsersController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'user';

    /**
     * List all users.
     *
     * @param \Cortex\Fort\DataTables\Managerarea\UsersDataTable $usersDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(UsersDataTable $usersDataTable)
    {
        return $usersDataTable->with([
            'id' => 'managerarea-users-index-table',
            'phrase' => trans('cortex/fort::common.users'),
        ])->render('cortex/tenants::managerarea.pages.datatable');
    }

    /**
     * List user logs.
     *
     * @param \Cortex\Fort\Models\User                    $user
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(User $user, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $user,
            'tabs' => 'managerarea.users.tabs',
            'phrase' => trans('cortex/fort::common.users'),
            'id' => "managerarea-users-{$user->getKey()}-logs-table",
        ])->render('cortex/tenants::managerarea.pages.datatable-logs');
    }

    /**
     * Get a listing of the resource activities.
     *
     * @param \Cortex\Fort\Models\User                          $user
     * @param \Cortex\Foundation\DataTables\ActivitiesDataTable $activitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activities(User $user, ActivitiesDataTable $activitiesDataTable)
    {
        return $activitiesDataTable->with([
            'resource' => $user,
            'tabs' => 'managerarea.users.tabs',
            'phrase' => trans('cortex/fort::common.users'),
            'id' => "managerarea-users-{$user->getKey()}-activities-table",
        ])->render('cortex/tenants::managerarea.pages.datatable-logs');
    }

    /**
     * Show the form for create/update of the given resource attributes.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\View\View
     */
    public function attributes(Request $request, User $user)
    {
        return view('cortex/fort::managerarea.pages.user-attributes', compact('user'));
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Fort\Http\Requests\Managerarea\UserAttributesFormRequest $request
     * @param \Cortex\Fort\Models\User                                         $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateAttributes(UserAttributesFormRequest $request, User $user)
    {
        $data = $request->validated();

        // Update profile
        $user->fill($data)->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/fort::messages.account.updated_attributes')],
        ]);
    }

    /**
     * Create new user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, User $user)
    {
        return $this->form($request, $user);
    }

    /**
     * Edit given user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, User $user)
    {
        return $this->form($request, $user);
    }

    /**
     * Show user create/edit form.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, User $user)
    {
        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();
        $currentUser = $request->user($this->getGuard());
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['male' => trans('cortex/fort::common.male'), 'female' => trans('cortex/fort::common.female')];

        $roles = $currentUser->can('superadmin')
            ? app('cortex.fort.role')->all()->pluck('name', 'id')->toArray()
            : $currentUser->roles->pluck('name', 'id')->toArray();

        $abilities = $currentUser->can('superadmin')
            ? app('cortex.fort.ability')->all()->pluck('title', 'id')->toArray()
            : $currentUser->abilities->pluck('title', 'id')->toArray();

        return view('cortex/fort::managerarea.pages.user', compact('user', 'abilities', 'roles', 'countries', 'languages', 'genders'));
    }

    /**
     * Store new user.
     *
     * @param \Cortex\Fort\Http\Requests\Managerarea\UserFormRequest $request
     * @param \Cortex\Fort\Models\User                             $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(UserFormRequest $request, User $user)
    {
        return $this->process($request, $user);
    }

    /**
     * Update given user.
     *
     * @param \Cortex\Fort\Http\Requests\Managerarea\UserFormRequest $request
     * @param \Cortex\Fort\Models\User                             $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(UserFormRequest $request, User $user)
    {
        return $this->process($request, $user);
    }

    /**
     * Process stored/updated user.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Fort\Models\User                $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, User $user)
    {
        // Prepare required input fields
        $data = $request->validated();

        ! $request->hasFile('profile_picture')
        || $user->addMediaFromRequest('profile_picture')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('profile_picture', config('cortex.fort.media.disk'));

        ! $request->hasFile('cover_photo')
        || $user->addMediaFromRequest('cover_photo')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('cover_photo', config('cortex.fort.media.disk'));

        // Save user
        $user->fill($data)->save();

        return intend([
            'url' => route('managerarea.users.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => 'user', 'id' => $user->username])],
        ]);
    }

    /**
     * Destroy given user.
     *
     * @param \Cortex\Fort\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return intend([
            'url' => route('managerarea.users.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'user', 'id' => $user->username])],
        ]);
    }
}
