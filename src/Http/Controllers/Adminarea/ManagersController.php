<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Fort\Models\Manager;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Fort\DataTables\Adminarea\ManagersDataTable;
use Cortex\Fort\Http\Requests\Adminarea\ManagerFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Fort\Http\Requests\Adminarea\ManagerAttributesFormRequest;

class ManagersController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'manager';

    /**
     * List all managers.
     *
     * @param \Cortex\Fort\DataTables\Adminarea\ManagersDataTable $managersDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(ManagersDataTable $managersDataTable)
    {
        return $managersDataTable->with([
            'id' => 'adminarea-managers-index-table',
            'phrase' => trans('cortex/fort::common.managers'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * List manager logs.
     *
     * @param \Cortex\Fort\Models\Manager                    $manager
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Manager $manager, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $manager,
            'tabs' => 'adminarea.managers.tabs',
            'phrase' => trans('cortex/fort::common.managers'),
            'id' => "adminarea-managers-{$manager->getKey()}-logs-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Get a listing of the resource activities.
     *
     * @param \Cortex\Fort\Models\Manager                          $manager
     * @param \Cortex\Foundation\DataTables\ActivitiesDataTable $activitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activities(Manager $manager, ActivitiesDataTable $activitiesDataTable)
    {
        return $activitiesDataTable->with([
            'resource' => $manager,
            'tabs' => 'adminarea.managers.tabs',
            'phrase' => trans('cortex/fort::common.managers'),
            'id' => "adminarea-managers-{$manager->getKey()}-activities-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-logs');
    }

    /**
     * Show the form for create/update of the given resource attributes.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Manager $manager
     *
     * @return \Illuminate\View\View
     */
    public function attributes(Request $request, Manager $manager)
    {
        return view('cortex/fort::adminarea.pages.manager-attributes', compact('manager'));
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\ManagerAttributesFormRequest $request
     * @param \Cortex\Fort\Models\Manager                                       $manager
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateAttributes(ManagerAttributesFormRequest $request, Manager $manager)
    {
        $data = $request->validated();

        // Update profile
        $manager->fill($data)->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/fort::messages.account.updated_attributes')],
        ]);
    }

    /**
     * Create new manager.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Manager $manager
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Manager $manager)
    {
        return $this->form($request, $manager);
    }

    /**
     * Edit given manager.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Manager $manager
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Manager $manager)
    {
        return $this->form($request, $manager);
    }

    /**
     * Show manager create/edit form.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Manager $manager
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Manager $manager)
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

        return view('cortex/fort::adminarea.pages.manager', compact('manager', 'abilities', 'roles', 'countries', 'languages', 'genders'));
    }

    /**
     * Store new manager.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\ManagerFormRequest $request
     * @param \Cortex\Fort\Models\Manager                             $manager
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(ManagerFormRequest $request, Manager $manager)
    {
        return $this->process($request, $manager);
    }

    /**
     * Update given manager.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\ManagerFormRequest $request
     * @param \Cortex\Fort\Models\Manager                             $manager
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(ManagerFormRequest $request, Manager $manager)
    {
        return $this->process($request, $manager);
    }

    /**
     * Process stored/updated manager.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Fort\Models\Manager                $manager
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Manager $manager)
    {
        // Prepare required input fields
        $data = $request->validated();

        ! $request->hasFile('profile_picture')
        || $manager->addMediaFromRequest('profile_picture')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('profile_picture', config('cortex.fort.media.disk'));

        ! $request->hasFile('cover_photo')
        || $manager->addMediaFromRequest('cover_photo')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('cover_photo', config('cortex.fort.media.disk'));

        // Save manager
        $manager->fill($data)->save();

        return intend([
            'url' => route('adminarea.managers.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => 'manager', 'id' => $manager->username])],
        ]);
    }

    /**
     * Destroy given manager.
     *
     * @param \Cortex\Fort\Models\Manager $manager
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Manager $manager)
    {
        $manager->delete();

        return intend([
            'url' => route('adminarea.managers.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'manager', 'id' => $manager->username])],
        ]);
    }
}
