<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Auth\Models\Ability;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Importers\DefaultImporter;
use Cortex\Foundation\DataTables\ImportLogsDataTable;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Auth\DataTables\Adminarea\AbilitiesDataTable;
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
            'id' => 'adminarea-abilities-index-table',
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
            'id' => "adminarea-abilities-{$ability->getRouteKey()}-logs-table",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Import abilities.
     *
     * @return \Illuminate\View\View
     */
    public function import()
    {
        return view('cortex/foundation::adminarea.pages.import', [
            'id' => 'adminarea-abilities-import',
            'tabs' => 'adminarea.abilities.tabs',
            'url' => route('adminarea.abilities.hoard'),
        ]);
    }

    /**
     * Hoard abilities.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     * @param \Cortex\Foundation\Importers\DefaultImporter       $importer
     *
     * @return void
     */
    public function hoard(ImportFormRequest $request, DefaultImporter $importer)
    {
        // Handle the import
        $importer->config['resource'] = $this->resource;
        $importer->handleImport();
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
            'id' => 'adminarea-abilities-import-logs-table',
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
        $roles = $request->user($this->getGuard())->can('superadmin')
            ? app('cortex.auth.role')->all()->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->roles->pluck('name', 'id')->toArray();

        $entityTypes = app('cortex.auth.ability')->distinct()->get(['entity_type'])->pluck('entity_type', 'entity_type')->toArray();

        asort($roles);

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
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/auth::common.ability'), 'identifier' => $ability->title])],
        ]);
    }

    /**
     * Destroy given ability.
     *
     * @param \Cortex\Auth\Models\Ability $ability
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Ability $ability)
    {
        $ability->delete();

        return intend([
            'url' => route('adminarea.abilities.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/auth::common.ability'), 'identifier' => $ability->title])],
        ]);
    }
}
