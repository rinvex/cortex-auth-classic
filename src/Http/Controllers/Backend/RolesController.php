<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Backend;

use Cortex\Fort\Models\Role;
use Illuminate\Http\Request;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Backend\RolesDataTable;
use Cortex\Fort\Http\Requests\Backend\RoleFormRequest;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return app(RolesDataTable::class)->with([
            'id' => 'cortex-fort-roles',
            'phrase' => trans('cortex/fort::common.roles'),
        ])->render('cortex/foundation::backend.partials.datatable');
    }

    /**
     * Display a listing of the resource logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function logs(Role $role)
    {
        return app(LogsDataTable::class)->with([
            'type' => 'roles',
            'resource' => $role,
            'id' => 'cortex-fort-roles-logs',
            'phrase' => trans('cortex/fort::common.roles'),
        ])->render('cortex/foundation::backend.partials.datatable-logs');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Backend\RoleFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleFormRequest $request)
    {
        return $this->process($request, new Role());
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Backend\RoleFormRequest $request
     * @param \Cortex\Fort\Models\Role                           $role
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RoleFormRequest $request, Role $role)
    {
        return $this->process($request, $role);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Role $role)
    {
        $role->delete();

        return intend([
            'url' => route('backend.roles.index'),
            'with' => ['warning' => trans('cortex/fort::messages.role.deleted', ['roleId' => $role->id])],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Role $role)
    {
        $abilities = app('rinvex.fort.ability')->all()->groupBy('resource')->map->pluck('name', 'id')->toArray();

        return view('cortex/fort::backend.forms.role', compact('role', 'abilities'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Cortex\Fort\Models\Role $role
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(Request $request, Role $role)
    {
        // Prepare required input fields
        $data = $request->all();

        // Save role
        $role->fill($data)->save();

        return intend([
            'url' => route('backend.roles.index'),
            'with' => ['success' => trans('cortex/fort::messages.role.saved', ['roleId' => $role->id])],
        ]);
    }
}
