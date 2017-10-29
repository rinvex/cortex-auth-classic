<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Tenantarea;

use Illuminate\Http\Request;
use Rinvex\Fort\Contracts\RoleContract;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Fort\DataTables\Tenantarea\RolesDataTable;
use Cortex\Fort\Http\Requests\Tenantarea\RoleFormRequest;
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
        ])->render('cortex/foundation::tenantarea.pages.datatable');
    }

    /**
     * Display a listing of the resource logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function logs(RoleContract $role)
    {
        return app(LogsDataTable::class)->with([
            'tab' => 'logs',
            'type' => 'roles',
            'resource' => $role,
            'id' => 'cortex-fort-roles-logs',
            'phrase' => trans('cortex/fort::common.roles'),
        ])->render('cortex/foundation::tenantarea.pages.datatable-tab');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\RoleFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleFormRequest $request)
    {
        return $this->process($request, app('rinvex.fort.role'));
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\RoleFormRequest $request
     * @param \Rinvex\Fort\Contracts\RoleContract                   $role
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RoleFormRequest $request, RoleContract $role)
    {
        return $this->process($request, $role);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Rinvex\Fort\Contracts\RoleContract $role
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(RoleContract $role)
    {
        $role->delete();

        return intend([
            'url' => route('tenantarea.roles.index'),
            'with' => ['warning' => trans('cortex/fort::messages.role.deleted', ['slug' => $role->slug])],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Rinvex\Fort\Contracts\RoleContract $role
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Request $request, RoleContract $role)
    {
        $owner = optional(optional(config('rinvex.tenants.tenant.active'))->owner)->id;

        $abilities = $request->user($this->getGuard())->id === $owner
            ? app('rinvex.fort.role')->forAllTenants()->where('slug', 'manager')->first()->abilities->groupBy('resource')->map->pluck('name', 'id')->toArray()
            : $request->user($this->getGuard())->allAbilities->groupBy('resource')->map->pluck('name', 'id')->toArray();

        return view('cortex/fort::tenantarea.forms.role', compact('role', 'abilities'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Rinvex\Fort\Contracts\RoleContract $role
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(Request $request, RoleContract $role)
    {
        // Prepare required input fields
        $data = $request->all();

        // Save role
        $role->fill($data)->save();

        return intend([
            'url' => route('tenantarea.roles.index'),
            'with' => ['success' => trans('cortex/fort::messages.role.saved', ['slug' => $role->slug])],
        ]);
    }
}
