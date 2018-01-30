<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Rinvex\Fort\Models\User;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Adminarea\UsersDataTable;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Fort\Http\Requests\Adminarea\UserFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Fort\Http\Requests\Adminarea\UserAttributesFormRequest;

class UsersController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'users';

    /**
     * Display a listing of the resource.
     *
     * @param \Cortex\Fort\DataTables\Adminarea\UsersDataTable $usersDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(UsersDataTable $usersDataTable)
    {
        return $usersDataTable->with([
            'id' => 'adminarea-users-index-table',
            'phrase' => trans('cortex/fort::common.users'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * Get a listing of the resource logs.
     *
     * @param \Rinvex\Fort\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(User $user, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $user,
            'tabs' => 'adminarea.users.tabs',
            'phrase' => trans('cortex/fort::common.users'),
            'id' => "adminarea-users-{$user->getKey()}-logs-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Get a listing of the resource activities.
     *
     * @param \Rinvex\Fort\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activities(User $user, ActivitiesDataTable $activitiesDataTable)
    {
        return $activitiesDataTable->with([
            'resource' => $user,
            'tabs' => 'adminarea.users.tabs',
            'phrase' => trans('cortex/fort::common.users'),
            'id' => "adminarea-users-{$user->getKey()}-activities-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Show the form for create/update of the given resource attributes.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Rinvex\Fort\Models\User $user
     *
     * @return \Illuminate\View\View
     */
    public function attributes(Request $request, User $user)
    {
        return view('cortex/fort::adminarea.pages.user-attributes', compact('user'));
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\UserAttributesFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateAttributes(UserAttributesFormRequest $request)
    {
        $data = $request->validated();
        $currentUser = $request->user($this->getGuard());

        // Update profile
        $currentUser->fill($data)->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/fort::messages.account.updated_attributes')],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Rinvex\Fort\Models\User $user
     *
     * @return \Illuminate\View\View
     */
    public function form(Request $request, User $user)
    {
        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();
        $authUser = $request->user($this->getGuard());
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['m' => trans('cortex/fort::common.male'), 'f' => trans('cortex/fort::common.female')];

        $roles = $authUser->isSuperadmin()
            ? app('rinvex.fort.role')->all()->pluck('name', 'id')->toArray()
            : $authUser->roles->pluck('name', 'id')->toArray();

        $abilities = $authUser->isSuperadmin()
            ? app('rinvex.fort.ability')->all()->groupBy('resource')->map->pluck('name', 'id')->toArray()
            : $authUser->allAbilities->groupBy('resource')->map->pluck('name', 'id')->toArray();

        return view('cortex/fort::adminarea.pages.user', compact('user', 'abilities', 'roles', 'countries', 'languages', 'genders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\UserFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(UserFormRequest $request)
    {
        return $this->process($request, app('rinvex.fort.user'));
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\UserFormRequest $request
     * @param \Rinvex\Fort\Models\User                             $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(UserFormRequest $request, User $user)
    {
        return $this->process($request, $user);
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Rinvex\Fort\Models\User                $user
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
            'url' => route('adminarea.users.index'),
            'with' => ['success' => trans('cortex/fort::messages.user.saved', ['username' => $user->username])],
        ]);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Rinvex\Fort\Models\User $user
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete(User $user)
    {
        $user->delete();

        return intend([
            'url' => route('adminarea.users.index'),
            'with' => ['warning' => trans('cortex/fort::messages.user.deleted', ['username' => $user->username])],
        ]);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Rinvex\Fort\Models\User          $user
     * @param \Spatie\MediaLibrary\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteMedia(User $user, Media $media)
    {
        $user->media()->where($media->getKeyName(), $media->getKey())->first()->delete();

        return intend([
            'url' => route('adminarea.users.edit', ['user' => $user]),
            'with' => ['warning' => trans('cortex/fort::messages.user.media_deleted')],
        ]);
    }
}
