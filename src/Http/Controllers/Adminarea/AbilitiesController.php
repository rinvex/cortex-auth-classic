<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Exception;
use Illuminate\Http\Request;
use Cortex\Auth\Models\Ability;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Importers\DefaultImporter;
use Cortex\Foundation\DataTables\ImportLogsDataTable;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Auth\DataTables\Adminarea\AbilitiesDataTable;
use Cortex\Foundation\DataTables\ImportRecordsDataTable;
use Cortex\Auth\Http\Requests\Adminarea\AbilityFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Auth\Http\Requests\Adminarea\AbilityFormProcessRequest;

class AbilitiesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = Ability::class;

    /**
     * List all abilities.
     *
     * @param \Cortex\Auth\DataTables\Adminarea\AbilitiesDataTable $abilitiesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(AbilitiesDataTable $abilitiesDataTable)
    {
        return $abilitiesDataTable->with([
            'id' => 'adminarea-abilities-index',
            'pusher' => ['entity' => 'ability', 'channel' => 'cortex.auth.abilities.index'],
        ])->render('cortex/foundation::adminarea.pages.datatable-index');
    }

    /**
     * List ability logs.
     *
     * @param \Cortex\Auth\Models\Ability                 $ability
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Ability $ability, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $ability,
            'tabs' => 'adminarea.abilities.tabs',
            'id' => "adminarea-abilities-{$ability->getRouteKey()}-logs",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Import abilities.
     *
     * @param \Cortex\Auth\Models\Ability                          $ability
     * @param \Cortex\Foundation\DataTables\ImportRecordsDataTable $importRecordsDataTable
     *
     * @return \Illuminate\View\View
     */
    public function import(Ability $ability, ImportRecordsDataTable $importRecordsDataTable)
    {
        return $importRecordsDataTable->with([
            'resource' => $ability,
            'tabs' => 'adminarea.abilities.tabs',
            'url' => route('adminarea.abilities.stash'),
            'id' => "adminarea-abilities-{$ability->getRouteKey()}-import",
        ])->render('cortex/foundation::adminarea.pages.datatable-dropzone');
    }

    /**
     * Stash abilities.
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
        $importer->handleImport();
    }

    /**
     * Hoard abilities.
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
                $fillable = collect($record['data'])->intersectByKeys(array_flip(app('cortex.auth.ability')->getFillable()))->toArray();

                tap(app('cortex.auth.ability')->firstOrNew($fillable), function ($instance) use ($record) {
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
     * List ability import logs.
     *
     * @param \Cortex\Foundation\DataTables\ImportLogsDataTable $importLogsDatatable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function importLogs(ImportLogsDataTable $importLogsDatatable)
    {
        return $importLogsDatatable->with([
            'resource' => trans('cortex/auth::common.ability'),
            'tabs' => 'adminarea.abilities.tabs',
            'id' => 'adminarea-abilities-import-logs',
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Create new ability.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Auth\Models\Role $ability
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Ability $ability)
    {
        return $this->form($request, $ability);
    }

    /**
     * Edit given ability.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AbilityFormRequest $request
     * @param \Cortex\Auth\Models\Role                                $ability
     *
     * @return \Illuminate\View\View
     */
    public function edit(AbilityFormRequest $request, Ability $ability)
    {
        return $this->form($request, $ability);
    }

    /**
     * Show ability create/edit form.
     *
     * @param \Illuminate\Http\Request    $request
     * @param \Cortex\Auth\Models\Ability $ability
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Ability $ability)
    {
        $roles = $request->user(app('request.guard'))->getManagedRoles();
        $entityTypes = app('cortex.auth.ability')->distinct()->get(['entity_type'])->pluck('entity_type', 'entity_type')->toArray();

        return view('cortex/auth::adminarea.pages.ability', compact('ability', 'roles', 'entityTypes'));
    }

    /**
     * Store new ability.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AbilityFormProcessRequest $request
     * @param \Cortex\Auth\Models\Ability                                    $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(AbilityFormProcessRequest $request, Ability $ability)
    {
        return $this->process($request, $ability);
    }

    /**
     * Update given ability.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AbilityFormProcessRequest $request
     * @param \Cortex\Auth\Models\Ability                                    $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AbilityFormProcessRequest $request, Ability $ability)
    {
        return $this->process($request, $ability);
    }

    /**
     * Process stored/updated ability.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Auth\Models\Ability             $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Ability $ability)
    {
        // Prepare required input fields
        $data = $request->validated();

        // Save ability
        $ability->fill($data)->save();

        return intend([
            'url' => route('adminarea.abilities.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/auth::common.ability'), 'identifier' => $ability->getRouteKey()])],
        ]);
    }

    /**
     * Destroy given ability.
     *
     * @param \Cortex\Auth\Models\Ability $ability
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Ability $ability)
    {
        $ability->delete();

        return intend([
            'url' => route('adminarea.abilities.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/auth::common.ability'), 'identifier' => $ability->getRouteKey()])],
        ]);
    }
}
