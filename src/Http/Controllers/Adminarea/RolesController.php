<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Cortex\Auth\Models\Role;
use Illuminate\Http\Request;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Importers\InsertImporter;
use Cortex\Auth\DataTables\Adminarea\RolesDataTable;
use Cortex\Foundation\Http\Requests\ImportFormRequest;
use Cortex\Auth\Http\Requests\Adminarea\RoleFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Auth\Http\Requests\Adminarea\RoleFormProcessRequest;

class RolesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'cortex.auth.models.role';

    /**
     * List all roles.
     *
     * @param \Cortex\Auth\DataTables\Adminarea\RolesDataTable $rolesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(RolesDataTable $rolesDataTable)
    {
        return $rolesDataTable->with([
            'id' => 'adminarea-cortex-auth-roles-index',
            'routePrefix' => 'adminarea.cortex.auth.roles',
            'pusher' => ['entity' => 'role', 'channel' => 'cortex.auth.roles.index'],
        ])->render('cortex/foundation::adminarea.pages.datatable-index');
    }

    /**
     * List role logs.
     *
     * @param \Cortex\Auth\Models\Role                    $role
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Role $role, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $role,
            'tabs' => 'adminarea.cortex.auth.roles.tabs',
            'id' => "adminarea-cortex-auth-roles-{$role->getRouteKey()}-logs",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Import roles.
     *
     * @param \Cortex\Foundation\Http\Requests\ImportFormRequest $request
     * @param \Cortex\Foundation\Importers\InsertImporter        $importer
     * @param \Cortex\Auth\Models\Role                           $role
     *
     * @return void
     */
    public function import(ImportFormRequest $request, InsertImporter $importer, Role $role)
    {
        $importer->withModel($role)->import($request->file('file'));
    }

    /**
     * Create new role.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Auth\Models\Role $role
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Role $role)
    {
        return $this->form($request, $role);
    }

    /**
     * Edit given role.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\RoleFormRequest $request
     * @param \Cortex\Auth\Models\Role                             $role
     *
     * @return \Illuminate\View\View
     */
    public function edit(RoleFormRequest $request, Role $role)
    {
        return $this->form($request, $role);
    }

    /**
     * Show role create/edit form.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Auth\Models\Role $role
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Role $role)
    {
        if (! $role->exists && $request->has('replicate') && $replicated = $role->resolveRouteBinding($request->input('replicate'))) {
            $role = $replicated->replicate();
        }

        $abilities = $request->user()->getManagedAbilityIds();

        return view('cortex/auth::adminarea.pages.role', compact('role', 'abilities'));
    }

    /**
     * Store new role.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\RoleFormProcessRequest $request
     * @param \Cortex\Auth\Models\Role                                    $role
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(RoleFormProcessRequest $request, Role $role)
    {
        return $this->process($request, $role);
    }

    /**
     * Update given role.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\RoleFormProcessRequest $request
     * @param \Cortex\Auth\Models\Role                                    $role
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(RoleFormProcessRequest $request, Role $role)
    {
        return $this->process($request, $role);
    }

    /**
     * Process stored/updated role.
     *
     * @param \Cortex\Foundation\Http\FormRequest $request
     * @param \Cortex\Auth\Models\Role            $role
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Role $role)
    {
        // Prepare required input fields
        $data = $request->validated();

        // Save role
        $role->fill($data)->save();

        return intend([
            'url' => route('adminarea.cortex.auth.roles.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/auth::common.role'), 'identifier' => $role->getRouteKey()])],
        ]);
    }

    /**
     * Destroy given role.
     *
     * @param \Cortex\Auth\Models\Role $role
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return intend([
            'url' => route('adminarea.cortex.auth.roles.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/auth::common.role'), 'identifier' => $role->getRouteKey()])],
        ]);
    }
}
