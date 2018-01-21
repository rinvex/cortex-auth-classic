<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Rinvex\Fort\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Adminarea\RolesDataTable;
use Cortex\Fort\Http\Requests\Adminarea\RoleFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class RolesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'roles';

    /**
     * {@inheritdoc}
     */
    protected $resourceActionWhitelist = ['assign'];

    /**
     * Display a listing of the resource.
     *
     * @param \Cortex\Fort\DataTables\Adminarea\RolesDataTable $rolesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(RolesDataTable $rolesDataTable)
    {
        return $rolesDataTable->with([
            'id' => 'adminarea-roles-index-table',
            'phrase' => trans('cortex/fort::common.roles'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * Get a listing of the resource logs.
     *
     * @param \Rinvex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Role $role)
    {
        return request()->ajax() && request()->wantsJson()
            ? app(LogsDataTable::class)->with(['resource' => $role])->ajax()
            : intend(['url' => route('adminarea.roles.edit', ['role' => $role]).'#logs-tab']);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Rinvex\Fort\Models\Role $role
     *
     * @return \Illuminate\View\View
     */
    public function form(Request $request, Role $role)
    {
        $abilities = $request->user($this->getGuard())->isSuperadmin()
            ? app('rinvex.fort.ability')->all()->groupBy('resource')->map->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->allAbilities->groupBy('resource')->map->pluck('name', 'id')->toArray();

        $logs = app(LogsDataTable::class)->with(['id' => "adminarea-roles-{$role->getKey()}-logs-table"])->html()->minifiedAjax(route('adminarea.roles.logs', ['role' => $role]));

        return view('cortex/fort::adminarea.pages.role', compact('role', 'abilities', 'logs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\RoleFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(RoleFormRequest $request)
    {
        return $this->process($request, app('rinvex.fort.role'));
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\RoleFormRequest $request
     * @param \Rinvex\Fort\Models\Role                             $role
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(RoleFormRequest $request, Role $role)
    {
        return $this->process($request, $role);
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @param \Rinvex\Fort\Models\Role                $role
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
            'url' => route('adminarea.roles.index'),
            'with' => ['success' => trans('cortex/fort::messages.role.saved', ['slug' => $role->slug])],
        ]);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Rinvex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete(Role $role)
    {
        $role->delete();

        return intend([
            'url' => route('adminarea.roles.index'),
            'with' => ['warning' => trans('cortex/fort::messages.role.deleted', ['slug' => $role->slug])],
        ]);
    }
}
