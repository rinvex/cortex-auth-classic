<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Managerarea;

use Cortex\Fort\Models\Role;
use Illuminate\Http\Request;
use Cortex\Fort\Models\Ability;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Managerarea\RolesDataTable;
use Cortex\Fort\Http\Requests\Managerarea\RoleFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class RolesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'role';

    /**
     * List all roles.
     *
     * @param \Cortex\Fort\DataTables\Managerarea\RolesDataTable $rolesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(RolesDataTable $rolesDataTable)
    {
        return $rolesDataTable->with([
            'id' => 'managerarea-roles-index-table',
            'phrase' => trans('cortex/fort::common.roles'),
        ])->render('cortex/tenants::managerarea.pages.datatable');
    }

    /**
     * List role logs.
     *
     * @param \Cortex\Fort\Models\Role                    $role
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Role $role, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $role,
            'tabs' => 'managerarea.roles.tabs',
            'phrase' => trans('cortex/fort::common.roles'),
            'id' => "managerarea-roles-{$role->getKey()}-logs-table",
        ])->render('cortex/tenants::managerarea.pages.datatable-logs');
    }

    /**
     * Create new role.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Role $role
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
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Role $role)
    {
        return $this->form($request, $role);
    }

    /**
     * Show role create/edit form.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Role $role)
    {
        $abilities = $request->user($this->getGuard())->can('superadmin') ? Ability::all()->pluck('title', 'id')->toArray()
            : $request->user($this->getGuard())->abilities->pluck('title', 'id')->toArray();

        return view('cortex/fort::managerarea.pages.role', compact('role', 'abilities'));
    }

    /**
     * Store new role.
     *
     * @param \Cortex\Fort\Http\Requests\Managerarea\RoleFormRequest $request
     * @param \Cortex\Fort\Models\Role                               $role
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(RoleFormRequest $request, Role $role)
    {
        return $this->process($request, $role);
    }

    /**
     * Update given role.
     *
     * @param \Cortex\Fort\Http\Requests\Managerarea\RoleFormRequest $request
     * @param \Cortex\Fort\Models\Role                               $role
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(RoleFormRequest $request, Role $role)
    {
        return $this->process($request, $role);
    }

    /**
     * Process stored/updated role.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Cortex\Fort\Models\Role                $role
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
            'url' => route('managerarea.roles.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => 'role', 'id' => $role->name])],
        ]);
    }

    /**
     * Destroy given role.
     *
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return intend([
            'url' => route('managerarea.roles.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'role', 'id' => $role->name])],
        ]);
    }
}
