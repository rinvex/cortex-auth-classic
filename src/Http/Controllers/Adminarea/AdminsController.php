<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Auth\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Auth\DataTables\Adminarea\AdminsDataTable;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Auth\Http\Requests\Adminarea\AdminFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Auth\Http\Requests\Adminarea\AdminAttributesFormRequest;

class AdminsController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'admin';

    /**
     * List all admins.
     *
     * @param \Cortex\Auth\DataTables\Adminarea\AdminsDataTable $adminsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(AdminsDataTable $adminsDataTable)
    {
        return $adminsDataTable->with([
            'id' => 'adminarea-admins-index-table',
            'phrase' => trans('cortex/auth::common.admins'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * List admin logs.
     *
     * @param \Cortex\Auth\Models\Admin                   $admin
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Admin $admin, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $admin,
            'tabs' => 'adminarea.admins.tabs',
            'phrase' => trans('cortex/auth::common.admins'),
            'id' => "adminarea-admins-{$admin->getKey()}-logs-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Get a listing of the resource activities.
     *
     * @param \Cortex\Auth\Models\Admin                         $admin
     * @param \Cortex\Foundation\DataTables\ActivitiesDataTable $activitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activities(Admin $admin, ActivitiesDataTable $activitiesDataTable)
    {
        return $activitiesDataTable->with([
            'resource' => $admin,
            'tabs' => 'adminarea.admins.tabs',
            'phrase' => trans('cortex/auth::common.admins'),
            'id' => "adminarea-admins-{$admin->getKey()}-activities-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Show the form for create/update of the given resource attributes.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Cortex\Auth\Models\Admin $admin
     *
     * @return \Illuminate\View\View
     */
    public function attributes(Request $request, Admin $admin)
    {
        return view('cortex/auth::adminarea.pages.admin-attributes', compact('admin'));
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AdminAttributesFormRequest $request
     * @param \Cortex\Auth\Models\Admin                                       $admin
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateAttributes(AdminAttributesFormRequest $request, Admin $admin)
    {
        $data = $request->validated();

        // Update profile
        $admin->fill($data)->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.account.updated_attributes')],
        ]);
    }

    /**
     * Create new admin.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Cortex\Auth\Models\Admin $admin
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Admin $admin)
    {
        return $this->form($request, $admin);
    }

    /**
     * Edit given admin.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Cortex\Auth\Models\Admin $admin
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Admin $admin)
    {
        return $this->form($request, $admin);
    }

    /**
     * Show admin create/edit form.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Cortex\Auth\Models\Admin $admin
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Admin $admin)
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
        $genders = ['male' => trans('cortex/auth::common.male'), 'female' => trans('cortex/auth::common.female')];

        $roles = $currentUser->can('superadmin')
            ? app('cortex.auth.role')->all()->pluck('name', 'id')->toArray()
            : $currentUser->roles->pluck('name', 'id')->toArray();

        $abilities = $currentUser->can('superadmin')
            ? app('cortex.auth.ability')->all()->pluck('title', 'id')->toArray()
            : $currentUser->abilities->pluck('title', 'id')->toArray();

        return view('cortex/auth::adminarea.pages.admin', compact('admin', 'abilities', 'roles', 'countries', 'languages', 'genders'));
    }

    /**
     * Store new admin.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AdminFormRequest $request
     * @param \Cortex\Auth\Models\Admin                             $admin
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(AdminFormRequest $request, Admin $admin)
    {
        return $this->process($request, $admin);
    }

    /**
     * Update given admin.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AdminFormRequest $request
     * @param \Cortex\Auth\Models\Admin                             $admin
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AdminFormRequest $request, Admin $admin)
    {
        return $this->process($request, $admin);
    }

    /**
     * Process stored/updated admin.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Auth\Models\Admin               $admin
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Admin $admin)
    {
        // Prepare required input fields
        $data = $request->validated();

        ! $request->hasFile('profile_picture')
        || $admin->addMediaFromRequest('profile_picture')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('profile_picture', config('cortex.auth.media.disk'));

        ! $request->hasFile('cover_photo')
        || $admin->addMediaFromRequest('cover_photo')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('cover_photo', config('cortex.auth.media.disk'));

        // Save admin
        $admin->fill($data)->save();

        return intend([
            'url' => route('adminarea.admins.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => 'admin', 'id' => $admin->username])],
        ]);
    }

    /**
     * Destroy given admin.
     *
     * @param \Cortex\Auth\Models\Admin $admin
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return intend([
            'url' => route('adminarea.admins.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'admin', 'id' => $admin->username])],
        ]);
    }
}
