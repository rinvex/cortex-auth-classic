<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
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
     * @param \Cortex\Fort\DataTables\Adminarea\UsersDataTable $usersDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(UsersDataTable $usersDataTable)
    {
        return $usersDataTable->with([
            'id' => 'cortex-users',
            'phrase' => trans('cortex/fort::common.users'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * Get a listing of the resource logs.
     *
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logs(UserContract $user)
    {
        return request()->ajax() && request()->wantsJson()
            ? app(LogsDataTable::class)->with(['resource' => $user])->ajax()
            : intend(['url' => route('adminarea.users.edit', ['user' => $user]).'#logs-tab']);
    }

    /**
     * Get a listing of the resource activities.
     *
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function activities(UserContract $user)
    {
        return request()->ajax() && request()->wantsJson()
            ? app(ActivitiesDataTable::class)->with(['resource' => $user])->ajax()
            : intend(['url' => route('adminarea.users.edit', ['user' => $user]).'#activities-tab']);
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
     * Delete the given resource from storage.
     *
     * @param \Rinvex\Fort\Contracts\UserContract $user
     * @param \Spatie\MediaLibrary\Models\Media   $media
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function deleteMedia(UserContract $user, Media $media)
    {
        $user->media()->where('id' , $media->id)->first()->delete();

        return intend([
            'url' => route('adminarea.users.edit', ['user' => $user]),
            'with' => ['warning' => trans('cortex/fort::messages.user.media_deleted')],
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
        $authUser = $request->user($this->getGuard());
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['m' => trans('cortex/fort::common.male'), 'f' => trans('cortex/fort::common.female')];

        $roles = $authUser->isSuperadmin()
            ? app('rinvex.fort.role')->all()->pluck('name', 'id')->toArray()
            : $authUser->roles->pluck('name', 'id')->toArray();

        $abilities = $authUser->isSuperadmin()
            ? app('rinvex.fort.ability')->all()->groupBy('resource')->map->pluck('name', 'id')->toArray()
            : $authUser->allAbilities->groupBy('resource')->map->pluck('name', 'id')->toArray();

        $logs = app(LogsDataTable::class)->with(['id' => 'logs-table'])->html()->minifiedAjax(route('adminarea.users.logs', ['user' => $user]));
        $activities = app(ActivitiesDataTable::class)->with(['id' => 'activities-table'])->html()->minifiedAjax(route('adminarea.users.activities', ['user' => $user]));

        return view('cortex/fort::adminarea.pages.user', compact('user', 'abilities', 'roles', 'countries', 'languages', 'genders', 'logs', 'activities'));
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

        ! $request->hasFile('profile_picture') || $user->addMediaFromRequest('profile_picture')
             ->toMediaCollection('profile_picture', config('cortex.fort.media.disk'));

        ! $request->hasFile('cover_photo') || $user->addMediaFromRequest('cover_photo')
             ->toMediaCollection('cover_photo', config('cortex.fort.media.disk'));

        // Save user
        $user->fill($data)->save();

        return intend([
            'url' => route('adminarea.users.index'),
            'with' => ['success' => trans('cortex/fort::messages.user.saved', ['username' => $user->username])],
        ]);
    }
}
