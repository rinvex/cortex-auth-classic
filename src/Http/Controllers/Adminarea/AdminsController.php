<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Exception;
use Illuminate\Http\Request;
use Cortex\Auth\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Importers\DefaultImporter;
use Cortex\Auth\DataTables\Adminarea\AdminsDataTable;
use Cortex\Foundation\DataTables\ActivitiesDataTable;
use Cortex\Foundation\DataTables\ImportLogsDataTable;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Foundation\DataTables\ImportRecordsDataTable;
use Cortex\Auth\Http\Requests\Adminarea\AdminFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Auth\Http\Requests\Adminarea\AdminAttributesFormRequest;

class AdminsController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = Admin::class;

    /**
     * List all admins.
     *
     * @param \Cortex\Auth\DataTables\Adminarea\AdminsDataTable $adminsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(AdminsDataTable $adminsDataTable)
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

        return $adminsDataTable->with([
            'id' => 'adminarea-admins-index',
            'countries' => $countries,
            'languages' => $languages,
            'genders' => $genders,
            'tags' => $tags,
        ])->render('cortex/auth::adminarea.pages.admins');
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
            'id' => "adminarea-admins-{$admin->getRouteKey()}-logs",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
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
            'id' => "adminarea-admins-{$admin->getRouteKey()}-activities",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
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
     * Import admins.
     *
     * @param \Cortex\Auth\Models\Admin                            $admin
     * @param \Cortex\Foundation\DataTables\ImportRecordsDataTable $importRecordsDataTable
     *
     * @return \Illuminate\View\View
     */
    public function import(Admin $admin, ImportRecordsDataTable $importRecordsDataTable)
    {
        return $importRecordsDataTable->with([
            'resource' => $admin,
            'tabs' => 'adminarea.admins.tabs',
            'url' => route('adminarea.admins.stash'),
            'id' => "adminarea-attributes-{$admin->getRouteKey()}-import",
        ])->render('cortex/foundation::adminarea.pages.datatable-dropzone');
    }

    /**
     * Stash admins.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     * @param \Cortex\Foundation\Importers\DefaultImporter       $importer
     *
     * @return void
     */
    public function stash(ImportFormRequest $request, DefaultImporter $importer)
    {
        // Handle the import
        $importer->config['resource'] = $this->resource;
        $importer->config['name'] = 'username';
        $importer->handleImport();
    }

    /**
     * Hoard admins.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function hoard(ImportFormRequest $request)
    {
        foreach ((array) $request->get('selected_ids') as $recordId) {
            $record = app('cortex.foundation.import_record')->find($recordId);

            try {
                $fillable = collect($record['data'])->intersectByKeys(array_flip(app('cortex.auth.admin')->getFillable()))->toArray();

                tap(app('cortex.auth.admin')->firstOrNew($fillable), function ($instance) use ($record) {
                    $instance->save() && $record->delete();
                });
            } catch (Exception $exception) {
                $record->notes = $exception->getMessage().(method_exists($exception, 'getMessageBag') ? "\n".json_encode($exception->getMessageBag())."\n\n" : '');
                $record->status = 'fail';
                $record->save();
            }
        }

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/foundation::messages.import_complete')],
        ]);
    }

    /**
     * List admin import logs.
     *
     * @param \Cortex\Foundation\DataTables\ImportLogsDataTable $importLogsDatatable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function importLogs(ImportLogsDataTable $importLogsDatatable)
    {
        return $importLogsDatatable->with([
            'resource' => trans('cortex/auth::common.admin'),
            'tabs' => 'adminarea.admins.tabs',
            'id' => 'adminarea-admins-import-logs',
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
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
    public function edit(AdminFormRequest $request, Admin $admin)
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

        $tags = app('rinvex.tags.tag')->pluck('name', 'id');
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $genders = ['male' => trans('cortex/auth::common.male'), 'female' => trans('cortex/auth::common.female')];
        $abilities = $request->user($this->getGuard())->getManagedAbilities();
        $roles = $request->user($this->getGuard())->getManagedRoles();

        return view('cortex/auth::adminarea.pages.admin', compact('admin', 'abilities', 'roles', 'countries', 'languages', 'genders', 'tags'));
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
                ->toMediaCollection('profile_picture', config('cortex.foundation.media.disk'));

        ! $request->hasFile('cover_photo')
        || $admin->addMediaFromRequest('cover_photo')
                ->sanitizingFileName(function ($fileName) {
                    return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
                })
                ->toMediaCollection('cover_photo', config('cortex.foundation.media.disk'));

        // Save admin
        $admin->fill($data)->save();

        return intend([
            'url' => route('adminarea.admins.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/auth::common.admin'), 'identifier' => $admin->username])],
        ]);
    }

    /**
     * Destroy given admin.
     *
     * @param \Cortex\Auth\Models\Admin $admin
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return intend([
            'url' => route('adminarea.admins.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/auth::common.admin'), 'identifier' => $admin->username])],
        ]);
    }
}
