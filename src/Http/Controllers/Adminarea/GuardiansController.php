<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Cortex\Foundation\Importers\InsertImporter;
use Exception;
use Illuminate\Http\Request;
use Cortex\Auth\Models\Guardian;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Auth\DataTables\Adminarea\GuardiansDataTable;
use Cortex\Auth\Http\Requests\Adminarea\GuardianFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class GuardiansController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'cortex.auth.models.guardian';

    /**
     * List all guardians.
     *
     * @param \Cortex\Auth\DataTables\Adminarea\GuardiansDataTable $guardiansDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(GuardiansDataTable $guardiansDataTable)
    {
        return $guardiansDataTable->with([
            'id' => 'adminarea-cortex-auth-guardians-index',
            'routePrefix' => 'adminarea.cortex.auth.guardians',
            'pusher' => ['entity' => 'guardian', 'channel' => 'cortex.auth.guardians.index'],
        ])->render('cortex/foundation::adminarea.pages.datatable-index');
    }

    /**
     * List guardian logs.
     *
     * @param \Cortex\Auth\Models\Guardian                $guardian
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Guardian $guardian, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $guardian,
            'tabs' => 'adminarea.cortex.auth.guardians.tabs',
            'id' => "adminarea-cortex-auth-guardians-{$guardian->getRouteKey()}-logs",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Import guardians.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     * @param \Cortex\Foundation\Importers\InsertImporter        $importer
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @return void
     */
    public function import(ImportFormRequest $request, InsertImporter $importer, Guardian $guardian)
    {
        $importer->withModel($guardian)->import($request->file('file'));
    }

    /**
     * Create new guardian.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Guardian $guardian)
    {
        return $this->form($request, $guardian);
    }

    /**
     * Edit given guardian.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Guardian $guardian)
    {
        return $this->form($request, $guardian);
    }

    /**
     * Show guardian create/edit form.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Guardian $guardian)
    {
        if (! $guardian->exists && $request->has('replicate') && $replicated = $guardian->resolveRouteBinding($request->input('replicate'))) {
            $guardian = $replicated->replicate();
        }

        $tags = app('rinvex.tags.tag')->pluck('name', 'id');

        return view('cortex/auth::adminarea.pages.guardian', compact('guardian', 'tags'));
    }

    /**
     * Store new guardian.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\GuardianFormRequest $request
     * @param \Cortex\Auth\Models\Guardian                             $guardian
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(GuardianFormRequest $request, Guardian $guardian)
    {
        return $this->process($request, $guardian);
    }

    /**
     * Update given guardian.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\GuardianFormRequest $request
     * @param \Cortex\Auth\Models\Guardian                             $guardian
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(GuardianFormRequest $request, Guardian $guardian)
    {
        return $this->process($request, $guardian);
    }

    /**
     * Process stored/updated guardian.
     *
     * @param \Cortex\Foundation\Http\FormRequest $request
     * @param \Cortex\Auth\Models\Guardian        $guardian
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Guardian $guardian)
    {
        // Prepare required input fields
        $data = $request->validated();

        // Save guardian
        $guardian->fill($data)->save();

        return intend([
            'url' => route('adminarea.cortex.auth.guardians.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/auth::common.guardian'), 'identifier' => $guardian->getRouteKey()])],
        ]);
    }

    /**
     * Destroy given guardian.
     *
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Guardian $guardian)
    {
        $guardian->delete();

        return intend([
            'url' => route('adminarea.cortex.auth.guardians.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/auth::common.guardian'), 'identifier' => $guardian->getRouteKey()])],
        ]);
    }
}
