<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Managerarea;

use Illuminate\Http\Request;
use Cortex\Auth\Models\Manager;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Importers\InsertImporter;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Auth\DataTables\Managerarea\ManagersDataTable;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Auth\Http\Requests\Managerarea\ManagerFormRequest;

class ManagersController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'cortex.auth.models.manager';

    /**
     * List all managers.
     *
     * @param \Cortex\Auth\DataTables\Managerarea\ManagersDataTable $managersDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(ManagersDataTable $managersDataTable)
    {
        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();

        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $tags = app('rinvex.tags.tag')->all()->groupBy('group')->map->pluck('name', 'id')->sortKeys();
        $genders = ['male' => trans('cortex/auth::common.male'), 'female' => trans('cortex/auth::common.female')];

        return $managersDataTable->with([
            'id' => 'managerarea-cortex-auth-managers-index',
            'countries' => $countries,
            'languages' => $languages,
            'genders' => $genders,
            'tags' => $tags,
            'routePrefix' => 'managerarea.cortex.auth.managers',
            'pusher' => ['entity' => 'manager', 'channel' => 'cortex.auth.managers.index'],
        ])->render('cortex/auth::managerarea.pages.managers');
    }

    /**
     * List manager logs.
     *
     * @param \Cortex\Auth\Models\Manager                 $manager
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Manager $manager, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $manager,
            'tabs' => 'managerarea.cortex.auth.managers.tabs',
            'id' => "managerarea-cortex-auth-managers-{$manager->getRouteKey()}-logs",
        ])->render('cortex/foundation::managerarea.pages.datatable-tab');
    }

    /**
     * Get a listing of the resource activities.
     *
     * @param \Cortex\Auth\Models\Manager                       $manager
     * @param \Cortex\Foundation\DataTables\ActivitiesDataTable $activitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activities(Manager $manager, ActivitiesDataTable $activitiesDataTable)
    {
        return $activitiesDataTable->with([
            'resource' => $manager,
            'tabs' => 'managerarea.cortex.auth.managers.tabs',
            'id' => "managerarea-cortex-auth-managers-{$manager->getRouteKey()}-activities",
        ])->render('cortex/foundation::managerarea.pages.datatable-tab');
    }

    /**
     * Import managers.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     * @param \Cortex\Foundation\Importers\InsertImporter        $importer
     * @param \Cortex\Auth\Models\Manager                        $manager
     *
     * @return void
     */
    public function import(ImportFormRequest $request, InsertImporter $importer, Manager $manager)
    {
        $importer->withModel($manager)->import($request->file('file'));
    }

    /**
     * Create new manager.
     *
     * @param \Illuminate\Http\Request    $request
     * @param \Cortex\Auth\Models\Manager $manager
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
     * @param \Illuminate\Http\Request    $request
     * @param \Cortex\Auth\Models\Manager $manager
     *
     * @return \Illuminate\View\View
     */
    public function edit(ManagerFormRequest $request, Manager $manager)
    {
        return $this->form($request, $manager);
    }

    /**
     * Show manager create/edit form.
     *
     * @param \Illuminate\Http\Request    $request
     * @param \Cortex\Auth\Models\Manager $manager
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Manager $manager)
    {
        if (! $manager->exists && $request->has('replicate') && $replicated = $manager->resolveRouteBinding($request->input('replicate'))) {
            $manager = $replicated->replicate();
        }

        $countries = collect(countries())->map(function ($country, $code) {
            return [
                'id' => $code,
                'text' => $country['name'],
                'emoji' => $country['emoji'],
            ];
        })->values();

        $tags = app('rinvex.tags.tag')->pluck('name', 'id');
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['male' => trans('cortex/auth::common.male'), 'female' => trans('cortex/auth::common.female')];
        $abilities = $request->user()->getManagedAbilityIds();
        $roles = $request->user()->getManagedRoles();

        return view('cortex/auth::managerarea.pages.manager', compact('manager', 'abilities', 'roles', 'countries', 'languages', 'genders', 'tags'));
    }

    /**
     * Store new manager.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\ManagerFormRequest $request
     * @param \Cortex\Auth\Models\Manager                               $manager
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
     * @param \Cortex\Auth\Http\Requests\Managerarea\ManagerFormRequest $request
     * @param \Cortex\Auth\Models\Manager                               $manager
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
     * @param \Cortex\Foundation\Http\FormRequest $request
     * @param \Cortex\Auth\Models\Manager         $manager
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
                ->toMediaCollection('profile_picture', config('cortex.foundation.media.disk'));

        ! $request->hasFile('cover_photo')
        || $manager->addMediaFromRequest('cover_photo')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('cover_photo', config('cortex.foundation.media.disk'));

        // Save manager
        $manager->fill($data)->save();

        return intend([
            'url' => route('managerarea.cortex.auth.managers.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/auth::common.manager'), 'identifier' => $manager->getRouteKey()])],
        ]);
    }

    /**
     * Destroy given manager.
     *
     * @param \Cortex\Auth\Models\Manager $manager
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Manager $manager)
    {
        $manager->delete();

        return intend([
            'url' => route('managerarea.cortex.auth.managers.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/auth::common.manager'), 'identifier' => $manager->getRouteKey()])],
        ]);
    }
}
